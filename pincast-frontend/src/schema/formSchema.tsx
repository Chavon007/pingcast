import { z } from "zod";

const phoneRegex = /^\+?[1-9]\d{7,14}$/;
export const FormSchema = z.discriminatedUnion("platform", [
  z.object({
    platform: z.literal("whatsapp"),
    location: z.string().min(1, "Location is required"),
   deliveryTime: z.string().min(1, "Delivery time is required"),
    platformHandle: z
      .string()
      .regex(phoneRegex, "Enter a valid whatsapp number"),
  }),
  z.object({
    platform: z.literal("email"),
    location: z.string().min(1, "Location is required"),
    deliveryTime: z.string().min(1, "Delivery time is required"),
    platformHandle: z.email({ message: "Please use a valid email address" }),
  }),
  z.object({
    platform: z.literal("telegram"),
    location: z.string().min(1, "Location is required"),
    deliveryTime: z.string().min(1, "Delivery time is required"),
    platformHandle: z
      .string()
      .min(1, "Please eneter a valid telegram username"),
  }),
  z.object({
    platform: z.literal("sms"),
    location: z.string().min(1, "Location is required"),
  deliveryTime: z.string().min(1, "Delivery time is required"),
    platformHandle: z.string().regex(phoneRegex, "Enter a valid phone number"),
  }),
]);

export type FormDTO = z.infer<typeof FormSchema>;
