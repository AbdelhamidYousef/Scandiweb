import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import * as helper from "../config/helper";
import * as config from "../config/config";
import Header from "../components/Header";
import Dvd from "../components/Dvd";
import Furniture from "../components/Furniture";
import Book from "../components/Book";
import Button from "../components/Button";
import Label from "../components/Label";
import Input from "../components/Input";
import ErrorMessage from "../components/ErrorMessage";

export default function AddProduct() {
  const [inputs, setInputs] = useState({});
  const [formErrors, setFormErrors] = useState({});
  const navigate = useNavigate();

  function handleChange(e) {
    const name = e.target.name,
      value = e.target.value;

    setInputs((state) => ({ ...state, [name]: value }));
  }

  const handleReset = () => {
    setInputs({});
    setFormErrors({});

    navigate('/');
  };

  const handleSubmit = async function (e) {
    e.preventDefault();

    const options = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Accept": "application/json",
      },
      body: JSON.stringify(inputs),
    };

    try {
      const result = await helper.fetchApi(config.API, options);

      if (result.errors) {
        setFormErrors(result.errors);

      } else {
        handleReset();
        navigate("/");
      }
    } catch (error) {
      console.error(`Adding Product Failed: ${error}`);
    }
  };

  useEffect(function () {
    document.title = "Add Product";
    return () => (document.title = document.querySelector("title").textContent);
  }, []);

  return (
    <>
      <Header title="Product Add">
        <Button bgColor="green" type="submit" form="product_form">
          Save
        </Button>
        <Button bgColor="red" onClick={handleReset}>
          Cancel
        </Button>
      </Header>

      <main className="form-container">
        <form
          onSubmit={handleSubmit}
          method="POST"
          id="product_form"
          className="form"
        >
          <div>
            <Label htmlFor="sku" />
            <Input
              name="sku"
              id="sku"
              value={inputs.sku || ""}
              onChange={handleChange}
              placeholder="SKU"
              classes="uppercase"
            />
            {formErrors.sku && <ErrorMessage content={formErrors.sku} />}
          </div>

          <div>
            <Label htmlFor="name" />
            <Input
              name="name"
              id="name"
              value={inputs.name || ""}
              onChange={handleChange}
              placeholder="Product Name"
            />
            {formErrors.name && <ErrorMessage content={formErrors.name} />}
          </div>

          <div>
            <Label htmlFor="price" content="Price (&#36;)" />
            <Input
              name="price"
              id="price"
              value={inputs.price || ""}
              onChange={handleChange}
              placeholder="Price"
            />
            {formErrors.price && <ErrorMessage content={formErrors.price} />}
          </div>

          <div className="my-3">
            <Label htmlFor="productType" content="Type Switcher" />

            <select
              name="type"
              id="productType"
              value={inputs.type || ""}
              onChange={handleChange}
              className="input"
            >
              <option>Choose A Type</option>
              <option value="dvd">DVD</option>
              <option value="furniture">Furniture</option>
              <option value="book">Book</option>
            </select>

            {formErrors.type && <ErrorMessage content={formErrors.type} />}
          </div>

          {inputs.type === "dvd" && (
            <Dvd
              inputs={inputs}
              handleChange={handleChange}
              formErrors={formErrors}
            />
          )}

          {inputs.type === "furniture" && (
            <Furniture
            inputs={inputs}
            handleChange={handleChange}
            formErrors={formErrors}
            />
          )}

          {inputs.type === "book" && (
            <Book
              inputs={inputs}
              handleChange={handleChange}
              formErrors={formErrors}
            />
          )}

          <div className="form_special-att">
            {config.specialAtt[inputs.type] || ""}
          </div>
        </form>
      </main>
    </>
  );
}
