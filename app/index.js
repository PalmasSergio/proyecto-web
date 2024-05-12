import  express  from "express";
import cookieParser from 'cookie-parser';


//Fix para __direname
import path from 'path';
import {fileURLToPath} from 'url';
const __dirname = path.dirname(fileURLToPath(import.meta.url));
import {methods as authentication} from "./controllers/authentication.controller.js"
import {methods as authorization} from "./middlewares/authorization.js";

//Server
const app = express();
app.set("port",4000);
app.listen(app.get("port"));
console.log("Servidor corriendo en puerto",app.get("port"));

//Configuración
app.use(express.static(__dirname + "/public"));
app.use(express.json());
app.use(cookieParser())


//Rutas
app.get("/",authorization.soloPublico, (req,res)=> res.sendFile(__dirname + "/pages/login.html"));
app.get("/register",authorization.soloPublico,(req,res)=> res.sendFile(__dirname + "/pages/register.html"));
app.get("/turnos",(req,res)=> res.sendFile(__dirname + "/pages/admin/turnos.html"));
app.get("/turno",(req,res)=> res.sendFile(__dirname + "/pages/admin/turno.php"));
app.get("/turno",(req,res)=> res.sendFile(__dirname + "/pages/admin/buscar_turno.php"));
app.get("/admin",authorization.soloAdmin,(req,res)=> res.sendFile(__dirname + "/pages/admin/admin.html"));
app.post("/api/login",authentication.login);
app.post("/api/register",authentication.register);
// Agregar la ruta estática para las imágenes
app.use('/imagene', express.static(path.join(__dirname, "/pages/admin/imagen/icono.png")));

