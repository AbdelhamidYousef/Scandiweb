export default function Header({ title, children }) {
  return (
    <header className="header">
      <h1 className="header_title">{title}</h1>

      <div className="header_btns">{children}</div>
    </header>
  );
}
