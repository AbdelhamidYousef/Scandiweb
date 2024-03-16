import { RouterProvider, createBrowserRouter } from "react-router-dom";
import Root from "./pages/Root";
import ProductList from "./pages/ProductList";
import AddProduct from "./pages/AddProduct";

const router = createBrowserRouter([
  {
    path: "/",
    element: <Root />,
    children: [
      {
        index: true,
        element: <ProductList />,
      },
      {
        path: "product/add",
        element: <AddProduct />,
      },
    ],
  },
]);

export default function App() {
  return <RouterProvider router={router} />;
}
