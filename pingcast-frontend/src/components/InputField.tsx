import type React from "react";
import type { FieldError, UseFormRegisterReturn } from "react-hook-form";

interface InputFieldProps extends React.InputHTMLAttributes<HTMLInputElement> {
  label: string;
  registration: Partial<UseFormRegisterReturn>;
  error?: FieldError;
  icon?: React.ReactNode;
  labelRight?: React.ReactNode;
}

const InputField: React.FC<InputFieldProps> = ({
  label,
  registration,
  error,
  labelRight,
  icon,
  className,
  type = "text",
  id,
  ...props
}) => {
  const inputId = id ?? registration.name;

  return (
    <div className="flex flex-col gap-1">
      <div className="flex items-center gap-1">
        {icon && <span className="text-[#74beec]">{icon}</span>}
        <label className="text-sm text-slate-500 font-sans font-medium" htmlFor={inputId}>{label}</label>
        {labelRight && <div>{labelRight}</div>}
      </div>
      <div className="w-full rounded-2xl bg-white/85 p-3">
        <input type={type} className="focus:outline-none  w-full text-sm font-sans text-slate-800 font-semibold placeholder:font-normal"  {...props} {...registration} />
      </div>

      {error?.message && <p className="font-sans text-red-500 text-sm">{error?.message}</p>}
    </div>
  );
};

export default InputField;
