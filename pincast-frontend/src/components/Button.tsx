import React from "react";
import { AiOutlineLoading3Quarters } from "react-icons/ai";
import { FiSend } from "react-icons/fi";

interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  children: React.ReactNode;
  isloading?: boolean;
  icon?: React.ReactNode;
  loadingText?: string;
}

const Button: React.FC<ButtonProps> = ({
  children,
  isloading,
  loadingText,
  icon,
  className = "",
  disabled,
  ...props
}) => {
  return (
    <button disabled={isloading || disabled} {...props}>
      {isloading ? (
        <>
          <AiOutlineLoading3Quarters className="animate-spin text-lh" />
          {loadingText}
        </>
      ) : (
        <>
          <FiSend /> {children}
        </>
      )}
    </button>
  );
};

export default Button;
