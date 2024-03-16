import ErrorMessage from "./ErrorMessage";
import Input from "./Input";
import Label from "./Label";

export default function Dvd({ inputs, handleChange, formErrors }) {
  return (
    <div>
      <Label htmlFor="size" content="Size (MB)" />
      <Input
        name="size"
        value={inputs.size || ""}
        onChange={handleChange}
        placeholder="Size"
      />
      {formErrors.size && <ErrorMessage content={formErrors.size} />}
    </div>
  );
}
