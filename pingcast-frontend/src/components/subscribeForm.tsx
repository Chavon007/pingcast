import { useEffect } from "react";
import { useWatch, useFormState, type UseFormReturn } from "react-hook-form";
import { LuMapPin } from "react-icons/lu";
import { PiTelegramLogoThin } from "react-icons/pi";
import InputField from "../components/InputField";
import SelectOption from "../components/Select";
import Button from "../components/Button";
import type { FormDTO } from "../schema/formSchema";
import DeliveryTime from "./deliveryTime";
const platformOptions = [
  { label: "Email", value: "email" },
  { label: "Telegram", value: "telegram" },
];

const platformFieldConfig = {
  email: {
    label: "Email address",
    placeholder: "you@example.com",
    type: "email",
  },
  telegram: {
    label: "Telegram username",
    placeholder: "@yourhandle",
    type: "text",
  },
} as const;

interface SubscribeFormFieldsProps {
  methods: UseFormReturn<FormDTO>;
  submitError: string | null;
  submitSuccess: string | null;
  subscriptionId: string | null;
}

export default function SubscribeFormFields({
  methods,
  submitError,
  submitSuccess,
  subscriptionId,
}: SubscribeFormFieldsProps) {
  const platform =
    useWatch({ control: methods.control, name: "platform" }) ?? "email";
  const { errors, isSubmitting } = useFormState({ control: methods.control });
  const fieldConfig = platformFieldConfig[platform];

  useEffect(() => {
    if (submitSuccess) {
      methods.resetField("location");
      methods.resetField("platformHandle");
      methods.resetField("hour");
      methods.resetField("minutes");
      methods.resetField("period");
    }
  }, [submitSuccess]);

  return (
    <>
      <InputField
        className=""
        label="Your location"
        icon={<LuMapPin />}
        type="text"
        placeholder="Lagos, Nigeria"
        registration={methods.register("location")}
        error={errors.location}
      />

      <div className="flex items-center gap-2 p-2">
        <SelectOption
          className="w-30 text-sm text-slate-800/80 focus:outline-none font-sans font-normal rounded-2xl bg-white/85 p-3"
          label="Send via"
          options={platformOptions}
          registration={methods.register("platform")}
          error={errors.platform}
        />
        <div className="flex-1">
          <InputField
            className="w-full"
            label={fieldConfig.label}
            type={fieldConfig.type}
            placeholder={fieldConfig.placeholder}
            registration={methods.register("platformHandle")}
            error={errors.platformHandle}
          />
        </div>
      </div>

      <DeliveryTime
        hourRegistration={methods.register("hour", {
          valueAsNumber: true,
        })}
        minuteRegistration={methods.register("minutes", {
          valueAsNumber: true,
        })}
        periodRegistration={methods.register("period")}
        hourError={errors.hour}
        minutesError={errors.minutes}
        periodError={errors.period}
      />

      {platform === "telegram" && submitSuccess && subscriptionId && (
        <a
          href={`https://t.me/PingcastWeatherBot?start=${subscriptionId}`}
          target="_blank"
          rel="noopener noreferrer"
          className="flex items-center justify-center gap-2 rounded-2xl bg-[#229ED9] hover:bg-[#229ED9]/85 text-white text-sm font-sans font-semibold p-3"
        >
          <PiTelegramLogoThin className="text-white" />
          Start chat with our Telegram bot
        </a>
      )}
      {platform === "telegram" && submitSuccess && subscriptionId && (
        <p className="text-xs text-slate-500 font-sans px-1">
          You must press "Start" in Telegram before we can send you reports.
        </p>
      )}

      <p className="text-xs px-1 font-sans text-slate-500 font-normal">
        Uses your local timezone (Africa/Lagos).
      </p>

      {submitError && (
        <p className="text-red-500 font-sans text-sm">{submitError}</p>
      )}
      {submitSuccess && (
        <p className="text-green-600 font-sans text-sm">{submitSuccess}</p>
      )}

      <Button
        type="submit"
        isloading={isSubmitting}
        loadingText="Subscribing..."
      >
        Subscribe
      </Button>
    </>
  );
}
