import { useEffect, useState } from "react";
import * as helper from "../config/helper";
import * as config from "../config/config";
import Header from "../components/Header";
import Button from "../components/Button";
import Product from "../components/Product";

export default function ProductList() {
  const [products, setProducts] = useState([]);
  const [selected, setSelected] = useState([]);

  function handleSelection(e, productId) {
    if (e.target.checked) {
      setSelected((selected) => [...selected, productId]);
    } else {
      setSelected((selected) => selected.filter((id) => id !== productId));
    }
  }

  async function handleMassDelete() {
    if (selected.length === 0) return;

    const options = {
      method: "DELETE",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
      },
      body: JSON.stringify({ ids: selected }),
    };

    try {
      await helper.fetchApi(config.API, options);

      // Refreshing the product list
      fetchProducts();
      setSelected([]);
    } catch (error) {
      console.error(`Delete request failed: ${error}`);
    }
  }

  useEffect(() => {
    fetchProducts();
  }, []);

  async function fetchProducts() {
    try {
      const result = await helper.fetchApi(config.API);

      setProducts(result);
    } catch (error) {
      console.error(`Connection Failed: ${error}`);
    }
  }

  return (
    <>
      <Header title="Product List">
        <Button type="link" to="/product/add" bgColor="green">
          ADD
        </Button>

        <Button
          bgColor="red"
          id="delete-product-btn"
          onClick={handleMassDelete}
        >
          MASS DELETE
        </Button>
      </Header>

      <main className="products-container">
        {products.map((product) => (
          <Product
            key={product.id}
            product={product}
            onChange={handleSelection}
          />
        ))}
      </main>
    </>
  );
}
