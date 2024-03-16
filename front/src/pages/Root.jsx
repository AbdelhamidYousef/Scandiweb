import { Outlet } from "react-router-dom";
import Footer from "../components/Footer";

export default function Root() {
  return (
    <div className="app-root">
      <Outlet />
      <Footer />
    </div>
  );
}
