export default function Label({ htmlFor, content = htmlFor }) {
  return (
    <label htmlFor={htmlFor} className="label">
      {content}
    </label>
  );
}
