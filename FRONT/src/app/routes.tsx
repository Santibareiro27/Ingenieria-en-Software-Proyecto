import { createBrowserRouter } from "react-router";
import Root from "./components/Root";
import Dashboard from "./components/Dashboard";
import ProyectosPage from "./components/ProyectosPage";
import SeguimientoPage from "./components/SeguimientoPage";
import MaterialesPage from "./components/MaterialesPage";
import DocumentacionPage from "./components/DocumentacionPage";
import ReportesPage from "./components/ReportesPage";
import AlertasPage from "./components/AlertasPage";
import MaquinariaPage from "./components/MaquinariaPage";
import NotFound from "./components/NotFound";

export const router = createBrowserRouter([
  {
    path: "/",
    Component: Root,
    children: [
      { index: true, Component: Dashboard },
      { path: "proyectos", Component: ProyectosPage },
      { path: "seguimiento", Component: SeguimientoPage },
      { path: "materiales", Component: MaterialesPage },
      { path: "documentacion", Component: DocumentacionPage },
      { path: "reportes", Component: ReportesPage },
      { path: "alertas", Component: AlertasPage },
      { path: "maquinaria", Component: MaquinariaPage },
      { path: "*", Component: NotFound },
    ],
  },
]);
