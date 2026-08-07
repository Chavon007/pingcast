import { z } from "zod";

export const FormSchema = z.discriminatedUnion("platform", [
  z.object({
    platform: z.literal("email"),
    location: z.string().min(1, "Location is required"),
    hour: z.coerce
      .number()
      .int()
      .min(1, "Hour must be between 1 and 12")
      .max(12, "Hour must be between 1 and 12"),
    minutes: z.coerce
      .number()
      .int()
      .min(0, "Minutes must be between 0 and 59")
      .max(59, "Minutes must be between 0 and 59"),

    period: z.enum(["AM", "PM"]),
    platformHandle: z.email({ message: "Please use a valid email address" }),
  }),
  z.object({
    platform: z.literal("telegram"),
    location: z.string().min(1, "Location is required"),
    hour: z.coerce
      .number()
      .int()
      .min(1, "Hour must be between 1 and 12")
      .max(12, "Hour must be between 1 and 12"),
    minutes: z.coerce
      .number()
      .int()
      .min(0, "Minutes must be between 0 and 59")
      .max(59, "Minutes must be between 0 and 59"),

    period: z.enum(["AM", "PM"]),
    platformHandle: z
      .string({ message: "This field is required" })
      .min(1, "Please eneter a valid telegram username"),
  }),
]);

export type FormDTO = z.infer<typeof FormSchema>;
