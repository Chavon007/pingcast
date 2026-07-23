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
    <div>
      <div>
        <label htmlFor={inputId}>{label}</label>
        {labelRight && <div>{labelRight}</div>}
      </div>
      <div>
        {icon && <span>{icon}</span>}
        <input type={type} className="" {...props} {...registration} />
      </div>

      {error?.message && <p>{error?.message}</p>}
    </div>
  );
};

export default InputField;
