import type React from "react";
import type { FieldError, UseFormRegisterReturn } from "react-hook-form";

interface Option {
  label: string;
  value: string;
}

interface SelectProps extends React.SelectHTMLAttributes<HTMLSelectElement> {
  label: string;
  registration: UseFormRegisterReturn;
  error?: FieldError;
  options: Option[];
}

const SelectOption: React.FC<SelectProps> = ({
  label,
  registration,
  error,
  options,
  id,
  className,
  ...props
}) => {
  const selectId = id ?? registration.name;
  return (
    <div>
      <label htmlFor={selectId}>{label}</label>
      <select className={className} id={selectId} {...registration} {...props}>
        {options.map((opt) => (
          <option key={opt.value} value={opt.value}>
            {opt.label}
          </option>
        ))}
      </select>
      {error?.message && <p>{error?.message}</p>}
    </div>
  );
};
export default SelectOption;
