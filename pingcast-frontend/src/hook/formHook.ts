import axios from "axios";
import type { FormDTO } from "../schema/formSchema";

const baseURL = import.meta.env.VITE_API_URL;
type SubscribePayload = FormDTO & {
  deliveryTime: string;
}

const subscribeUser = async (data: SubscribePayload) => {
  const response = await axios.post(`${baseURL}/subscribe`, data, {
    headers: {
      "Content-Type": "application/json",
    },
  });

  return response.data;
};

export default subscribeUser
