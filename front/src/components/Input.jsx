export default function Input({
  type = "text",
  name,
  id = name,
  value,
  onChange,
  placeholder = name,
  required = false,
  classes,
}) {
  return (
    <input
      type={type}
      name={name}
      id={id}
      value={value}
      onChange={onChange}
      placeholder={placeholder}
      required={required}
      className={`${classes} input`}
    />
  );
}
