import ErrorMessage from "./ErrorMessage";
import Input from "./Input";
import Label from "./Label";

export default function Book({ inputs, handleChange, formErrors }) {
  return (
    <div>
      <Label htmlFor="weight" content="Weight (KG)" />
      <Input
        name="weight"
        value={inputs.weight || ""}
        onChange={handleChange}
        placeholder="Weight"
      />
      {formErrors.weight && <ErrorMessage content={formErrors.weight} />}
    </div>
  );
}
