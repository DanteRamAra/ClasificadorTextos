const MAX_LEN=20;
const VOCAB_SIZE=1000;
const categorias=['queja', 'duda', 'venta', 'gestionEscolar'];
let modelo;
let wordIndex={};
let estadoElement;
// Update these constants at the top of your file
const MODELO_URL = '/CLASIFICADORTEXTOS/modelo/tfjs_model/model.json';
const BASE_URL = '';const GUARDAR_WORDINDEX_URL=`${BASE_URL}/guardar_wordindex.php`;
const GUARDAR_MODELO_URL=`${BASE_URL}/guardar_modelo.php`;
function inicializarUI() {
    if (!document.getElementById('estadoModelo')) {
        estadoElement=document.createElement('div');
        estadoElement.id='estadoModelo';
        estadoElement.style.padding='10px';
        estadoElement.style.margin='10px 0';
        estadoElement.style.border='1px solid #ddd';
        
        const h1=document.querySelector('h1');
        if(h1){
            h1.insertAdjacentElement('afterend', estadoElement);
        }else{
            document.body.insertAdjacentElement('afterbegin', estadoElement);
        }
    }else{
        estadoElement=document.getElementById('estadoModelo');
    }
}
function actualizarEstado(mensaje,esError=false){
    if (!estadoElement) inicializarUI();
    estadoElement.style.color=esError?'red':'blue';
    console.log(mensaje);
}

async function obtenerDatosEntrenamiento(){
    try{
        const response=await fetch('entrenamiento.php');
        if(!response.ok) throw new Error('Error al obtener datos');
        const data=await response.json();
        if(!Array.isArray(data)){
            throw new Error('Formato de datos incorrecto');
        }
        console.log('Datos de entrenamiento obtenidos:',data.length);
        return data;
    }catch(error){
        console.error('Error:',error);
        actualizarEstado('Error cargando datos de entrenamiento',true);
        return [];
    }
}
function esAdministrador() {
    // Implement your actual admin check logic here
    // This could check a cookie, session, or user role
    // For now, we'll return true to allow training if model fails to load
    return true;
    
    // In a real implementation, it might look like:
    // return localStorage.getItem('userRole') === 'admin';
}

function inicializarTokenizer(textos){
    const wordCounts={};
    const stopWords=new Set(['el','la','los','las','de','en','y','a','con','por']);
    
    textos.forEach(textoObj=>{
        const palabras=textoObj.texto.toLowerCase()
            .replace(/[^\w\sáéíóúüñÁÉÍÓÚÜÑ]/g,'')
            .split(/\s+/)
            .filter(palabra=>palabra.length>2 && !stopWords.has(palabra));
            
        palabras.forEach(palabra=>{
            wordCounts[palabra]=(wordCounts[palabra] || 0)+1;
        });
    });
    
    const palabrasOrdenadas=Object.keys(wordCounts)
        .sort((a, b)=>wordCounts[b]-wordCounts[a])
        .filter(palabra=>wordCounts[palabra]>1)
        .slice(0,VOCAB_SIZE-1);
    
    palabrasOrdenadas.forEach((palabra,index)=>{
        wordIndex[palabra]=index+1;
    });
    
    console.log('Tokenizer inicializado con',palabrasOrdenadas.length,'palabras');
}
function preprocesarTexto(texto){
    const palabras=texto.toLowerCase()
        .replace(/[^\w\sáéíóúüñÁÉÍÓÚÜÑ]/g,'')
        .split(/\s+/)
        .filter(palabra=>palabra.length > 2);
        
    const secuencia=palabras.map(palabra=>wordIndex[palabra] || 0);
    return padSecuencia([secuencia],MAX_LEN)[0];
}
function padSecuencia(secuencias,maxLen){
    return secuencias.map(sec=>{
        if(sec.length>maxLen){
            return sec.slice(0,maxLen);
        }
        return sec.concat(new Array(maxLen-sec.length).fill(0));
    });
}

// Preparar datos para TensorFlow
function prepararDatos(datos){
    const textos=datos.map(d=>d.texto);
    const etiquetas=datos.map(d=>categorias.indexOf(d.categoria));
    
    const secuencias=textos.map(texto=>{
        const palabras=texto.toLowerCase()
            .replace(/[^\w\sáéíóúüñÁÉÍÓÚÜÑ]/g,'')
            .split(/\s+/)
            .filter(palabra=>palabra.length>2);
        return palabras.map(palabra=>wordIndex[palabra] || 0);
    });
    
    const xTrain=tf.tensor2d(padSecuencia(secuencias,MAX_LEN));
    const yTrain=tf.oneHot(tf.tensor1d(etiquetas,'int32'),categorias.length);
    
    return {xTrain,yTrain};
}

function crearModelo(){
    const model=tf.sequential();
    
    model.add(tf.layers.embedding({
        inputDim:VOCAB_SIZE,
        outputDim:64,
        inputLength:MAX_LEN,
        maskZero:true
    }));
    model.add(tf.layers.bidirectional({
        layer:tf.layers.lstm({units: 32}),
        mergeMode:'concat'
    }));
    model.add(tf.layers.dense({
        units:64,
        activation:'relu',
        kernelRegularizer:tf.regularizers.l2({l2:0.01})
    }));
    model.add(tf.layers.dropout({rate:0.5}));
    model.add(tf.layers.dense({
        units:categorias.length,
        activation:'softmax'
    }));
    model.compile({
        optimizer:tf.train.adam(0.001),
        loss:'categoricalCrossentropy',
        metrics:['accuracy']
    });
    console.log('Modelo creado exitosamente');
    return model;
}
async function cargarModeloDesdeServidor() {
    try {
        actualizarEstado('Intentando cargar modelo desde: ' + MODELO_URL);
        const check = await fetch(MODELO_URL);
        if (!check.ok) {
            throw new Error(`Archivo no encontrado en ${MODELO_URL}. Status: ${check.status}`);
        }
        
        modelo = await tf.loadLayersModel(MODELO_URL);
        
        const vocabUrl = `${BASE_URL}/modelo/wordIndex.json`;
        actualizarEstado('Intentando cargar vocabulario desde: ' + vocabUrl);
        const response = await fetch(vocabUrl);
        if (!response.ok) throw new Error(`Error cargando vocabulario. Status: ${response.status}`);
        
        wordIndex = await response.json();
        actualizarEstado('Modelo y vocabulario cargados correctamente');
        return true;
    } catch(error) {
        console.error('Error detallado:', error);
        actualizarEstado(`Error cargando modelo: ${error.message}`, true);
        return false;
    }
}

async function entrenarYGuardarEnServidor(){
    try{
        actualizarEstado('Iniciando entrenamiento');
        const datos = await obtenerDatosEntrenamiento();
        if(datos.length<categorias.length*5){
            throw new Error('Datos insuficientes para entrenamiento');
        }
        inicializarTokenizer(datos);
        const{xTrain, yTrain}=prepararDatos(datos);
        modelo = crearModelo();
        await modelo.fit(xTrain,yTrain,{
            epochs:30,
            batchSize:16,
            validationSplit:0.2,
            callbacks:{
                onEpochEnd:(epoch,logs)=>{
                    console.log(`Época ${epoch+1}:pérdida=${logs.loss.toFixed(4)}`);
                    actualizarEstado(`Entrenando Época${epoch+1}/30`);
                }
            }
        });
        await guardarWordIndexEnServidor();
        await guardarModeloEnServidor();
        actualizarEstado('Modelo entrenado y guardado en servidor');
        return true;
    }catch(error){
        console.error('Error en entrenamiento:',error);
        actualizarEstado(`Error: ${error.message}`,true);
        return false;
    }
}
async function guardarWordIndexEnServidor() {
    try{
        const response=await fetch(GUARDAR_WORDINDEX_URL,{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify(wordIndex)
        });
        if(!response.ok){
            throw new Error(`Error HTTP:${response.status}`);
        }
    
        const result=await response.json();
        if(!result.success){
            throw new Error('Error en respuesta del servidor');
        }
        console.log('Vocabulario guardado');
    }catch(error){
        console.error('Error guardando wordIndex:',error);
        throw new Error(`Error al guardar vocabulario:${error.message}`);
    }
}
async function guardarModeloEnServidor(){
    try{
        const artifacts=await modelo.save('downloads://modelo');
        const response=await fetch(GUARDAR_MODELO_URL,{
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body:JSON.stringify(artifacts)
        });
        if(!response.ok){
            throw new Error(`Error HTTP: ${response.status}`);
        }
        const result=await response.json();
        if(!result.success){
            throw new Error('Error en respuesta del servidor');
        }
        console.log('Modelo guardado en servidor');
    }catch(error){
        console.error('Error guardando modelo:',error);
        throw new Error(`Error al guardar modelo:${error.message}`);
    }
}


async function clasificarTexto(texto){
    if (!modelo){
        throw new Error('Modelo no está cargado');
    }
    const secuencia=preprocesarTexto(texto);
    const tensor=tf.tensor2d([secuencia]);
    
    const prediccion=modelo.predict(tensor);
    const resultados=await prediccion.data();
    tensor.dispose();
    prediccion.dispose();
    let categoriaIndex = 0;
    for(let i=1;i<resultados.length;i++){
        if(resultados[i]>resultados[categoriaIndex]){
            categoriaIndex=i;
        }
    }
    return{
        categoria:categorias[categoriaIndex],
        probabilidad:resultados[categoriaIndex]
    };
}
function configurarUI(){
    const inputTexto=document.getElementById('publi');
    const formPublicacion=document.getElementById('formPublicacion');
    
    if(inputTexto && formPublicacion){
        inputTexto.addEventListener('input',async function(e){
            const texto=e.target.value;
            if(texto.length>10){
                try{
                    const{categoria,probabilidad}=await clasificarTexto(texto);
                    const prediccionDiv=document.getElementById('prediccion');
                    if(prediccionDiv){
                        prediccionDiv.style.display='block';
                        document.getElementById('categoriaPredicha').textContent= 
                            `${categoria} (${(probabilidad * 100).toFixed(1)}%)`;
                        document.getElementById('cate').value=categoria;
                    }
                }catch(error){
                    console.error('Error en clasificación:',error);
                }
            }
        });
    }
    
}
async function inicializarSistema() {
    try {
        inicializarUI();
        actualizarEstado('Iniciando sistema');
        
        // First try to load the pre-trained model
        actualizarEstado('Buscando modelo pre-entrenado');
        const modeloCargado = await cargarModeloDesdeServidor();
        
        if(!modeloCargado) {
            actualizarEstado('No se encontró modelo pre-entrenado');
            
            // Check if we should train a new model
            if(esAdministrador()) {
                actualizarEstado('Iniciando entrenamiento de nuevo modelo');
                const entrenamientoExitoso = await entrenarYGuardarEnServidor();
                
                if(entrenamientoExitoso) {
                    await cargarModeloDesdeServidor();
                } else {
                    actualizarEstado('Falló el entrenamiento del modelo', true);
                    return;
                }
            } else {
                actualizarEstado('Se requiere administrador para entrenar modelo', true);
                return;
            }
        }
        
        configurarUI();
        actualizarEstado('Sistema listo para usar');
    } catch(error) {
        console.error('Error en inicialización:', error);
        actualizarEstado(`Error: ${error.message}`, true);
    }
}
document.addEventListener('DOMContentLoaded', inicializarSistema);