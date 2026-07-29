import { z } from "zod";

export const FormSchema = z.discriminatedUnion("platform", [
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
      .string({ message: "This field is required" })
      .min(1, "Please eneter a valid telegram username"),
  }),
]);

export type FormDTO = z.infer<typeof FormSchema>;
