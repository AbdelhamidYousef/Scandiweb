export default function Product({ product, onChange }) {
  const { id, sku, name, price, attribute } = product;

  return (
    <label className="product">
      <input
        type="checkbox"
        onChange={(e) => onChange(e, id)}
        className="delete-checkbox product_checkbox"
      />

      <p>{sku}</p>
      <p>{name}</p>
      <p>{price} $</p>
      <p>{attribute}</p>
    </label>
  );
}
