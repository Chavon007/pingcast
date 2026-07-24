import { useEffect } from "react";
import { useWatch, useFormState, type UseFormReturn } from "react-hook-form";
import { LuMapPin } from "react-icons/lu";
import InputField from "../components/InputField";
import SelectOption from "../components/Select";
import Button from "../components/Button";
import type { FormDTO } from "../schema/formSchema";

const platformOptions = [
  { label: "WhatsApp", value: "whatsapp" },
  { label: "SMS", value: "sms" },
  { label: "Email", value: "email" },
  { label: "Telegram", value: "telegram" },
];

const platformFieldConfig = {
  whatsapp: {
    label: "WhatsApp number",
    placeholder: "+2348000000000",
    type: "tel",
  },
  sms: { label: "Phone number", placeholder: "+2348000000000", type: "tel" },
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
}

export default function SubscribeFormFields({
  methods,
  submitError,
  submitSuccess,
}: SubscribeFormFieldsProps) {
  const platform =
    useWatch({ control: methods.control, name: "platform" }) ?? "whatsapp";
  const { errors, isSubmitting } = useFormState({ control: methods.control });
  const fieldConfig = platformFieldConfig[platform];

  useEffect(() => {
    methods.resetField("platformHandle");
  }, [platform]);

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
          className="w-30 text-sm text-slate-800/80 focus:outline-none font-sans font-normal  rounded-2xl bg-white/85 p-3"
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

      <InputField
        label="Preferred delivery time"
        type="text"
        placeholder="07:00 AM"
        registration={methods.register("deliveryTime")}
        error={errors.deliveryTime}
      />
      <p className="text-xs px-1 font-sans text-slate-500 font-normal">
        Uses your local timezone (Africa/Lagos).
      </p>

      {submitError && (
        <p className="text-red-500 font-sans text-sm">{submitError}</p>
      )}
      {submitSuccess && (
        <p className="text-red-500 font-sans text-sm">{submitSuccess}</p>
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
