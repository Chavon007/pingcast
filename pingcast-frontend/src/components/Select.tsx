import type React from "react";
import type { FieldError, UseFormRegisterReturn } from "react-hook-form";
import { RiArrowDropDownLine } from "react-icons/ri";
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
    <div className="flex flex-col gap-1">
      <label
        className="text-sm text-slate-500 font-sans font-medium"
        htmlFor={selectId}
      >
        {label}
      </label>
      <div className="relative">
        <select
          className={`appearance-none pr-8 ${className ?? ""}`}
          id={selectId}
          {...registration}
          {...props}
        >
          {options.map((opt) => (
            <option className="text-xs focus:outline-none" key={opt.value} value={opt.value}>
              {opt.label}
            </option>
          ))}
        </select>
        <RiArrowDropDownLine className="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
      </div>
      {error?.message && (
        <p className="font-sans text-red-500 text-sm">{error?.message}</p>
      )}
    </div>
  );
};
export default SelectOption;
