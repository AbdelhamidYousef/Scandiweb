import { Link } from "react-router-dom";

export default function Button({
  bgColor,
  type,
  to,
  form,
  id = "",
  onClick,
  children = "Click",
}) {
  if (type === "link")
    return (
      <Link className={`${id} btn btn--${bgColor}`} to={to}>
        {children}
      </Link>
    );

  return (
    <button
      className={`${id} btn btn--${bgColor}`}
      type={type}
      form={form}
      onClick={onClick}
    >
      {children}
    </button>
  );
}
