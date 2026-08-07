
import type { FieldError, UseFormRegisterReturn } from "react-hook-form";

interface deliveryInputProps {
  hourRegistration?: UseFormRegisterReturn;
  minuteRegistration: UseFormRegisterReturn;
  periodRegistration: UseFormRegisterReturn;
  hourError?: FieldError;
  minutesError?: FieldError;
  periodError?: FieldError;
  className?: string;
}

function DeliveryTime({
  hourRegistration,
  minuteRegistration,
  periodRegistration,
  hourError,
  minutesError,
  periodError,
  className,
}: deliveryInputProps) {
  return (
    <div className={`flex flex-col gap-1 ${className}`}>
      <div className="flex items-center gap-2">
        <input
          type="number"
          min="1"
          max="12"
          placeholder="06"
          className="w-16 focus:outline-none text-sm font-sans text-slate-800 font-semibold placeholder:font-normal bg-white/85 rounded-2xl p-3"
          {...hourRegistration}
        />

        <span className="text-slate-600 font-semibold">:</span>

        <input
          type="number"
          min="0"
          max="59"
          placeholder="12"
          className="w-16 focus:outline-none text-sm font-sans text-slate-800 font-semibold placeholder:font-normal bg-white/85 rounded-2xl p-3"
          {...minuteRegistration}
        />

        <select
          className="w-20 focus:outline-none text-xs font-sans text-slate-800 font-normal bg-white/85 rounded-2xl p-3"
          {...periodRegistration}
        >
          <option value="AM">AM</option>
          <option value="PM">PM</option>
        </select>
      </div>

      {(hourError || minutesError || periodError) && (
        <p className="font-sans text-red-500 text-sm">
          {hourError?.message ||
            minutesError?.message ||
            periodError?.message}
        </p>
      )}
    </div>
  );
}

export default DeliveryTime;
