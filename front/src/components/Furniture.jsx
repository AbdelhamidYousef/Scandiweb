import ErrorMessage from "./ErrorMessage";
import Input from "./Input";
import Label from "./Label";

export default function Furniture({ inputs, handleChange, formErrors }) {
  return (
    <>
      <div>
        <Label htmlFor="height" content="Height (CM)" />
        <Input
          name="height"
          value={inputs.height || ""}
          onChange={handleChange}
        />
        {formErrors.height && <ErrorMessage content={formErrors.height} />}
      </div>

      <div>
        <Label htmlFor="width" content="Width (CM)" />
        <Input
          name="width"
          value={inputs.width || ""}
          onChange={handleChange}
        />
        {formErrors.width && <ErrorMessage content={formErrors.width} />}
      </div>

      <div>
        <Label htmlFor="length" content="Length (CM)" />
        <Input
          name="length"
          value={inputs.length || ""}
          onChange={handleChange}
        />
        {formErrors.length && <ErrorMessage content={formErrors.length} />}
      </div>
    </>
  );
}
