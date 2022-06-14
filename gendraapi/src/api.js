// Importamos Axios 
import axios from 'axios';
 
// Definimos la ruta local del servidor Vue JS 
export default axios.create({
    baseURL: 'http://127.0.0.1:8000/'
});