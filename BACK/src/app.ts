import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';

import authRouter from './routes/auth';
import proyectosRouter from './routes/proyectos';
import planificacionRouter from './routes/planificacion';
import avancesRouter from './routes/avances';
import { errorHandler } from './middleware/errorHandler';

dotenv.config();

const app = express();
const PORT = process.env.PORT ?? 3000;

app.use(cors());
app.use(express.json());

app.use('/api/auth', authRouter);                 // /login, /register, /me
app.use('/api/proyectos', proyectosRouter);
app.use('/api/proyectos', planificacionRouter);   // /:proyectoId/planificacion
app.use('/api/planificacion', avancesRouter);     // /:planId/avances y /avance/:id

app.use(errorHandler);

app.listen(PORT, () => {
  console.log(`Servidor corriendo en http://localhost:${PORT}`);
});

export default app;
