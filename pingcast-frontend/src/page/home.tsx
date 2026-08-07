import { useState } from "react";
import { FiCloudRain } from "react-icons/fi";
import { IoSunnyOutline } from "react-icons/io5";
import { LuWind, LuSparkles } from "react-icons/lu";
import { CiCloud } from "react-icons/ci";
import Form from "../components/Form";
import subscribeUser from "../hook/formHook";
import { FormSchema, type FormDTO } from "../schema/formSchema";
import SubscribeFormFields from "../components/subscribeForm";

const about = [
  { title: "Personalized", icon: <IoSunnyOutline /> },
  { title: "Rain-aware", icon: <FiCloudRain /> },
  { title: "On your schedule", icon: <LuWind /> },
];

function Home() {
  const [submitError, setSubmitError] = useState<string | null>(null);
  const [submitSuccess, setSubmitSuccess] = useState<string | null>(null);
  const [subscriptionId, setSubscriptionId] = useState<string | null>(null);
  const handleSubmit = async (data: FormDTO) => {
    const formattedTime = `${String(data.hour).padStart(2, "0")}:${String(data.minutes).padStart(2, "0")} ${data.period}`;
    setSubmitError(null);
    setSubmitSuccess(null);
    try {
      const response = await subscribeUser({
        ...data,
        deliveryTime: formattedTime,
      });
      setSubmitSuccess("Successfully subscribed");
      setSubscriptionId(response.data.id);

      setTimeout(() => {
        setSubmitSuccess(null);
        setSubscriptionId(null);
      }, 5000);
    } catch (err) {
      setSubmitError("Something went wrong. Please try again.");
    }
  };
  return (
    <div className="bg-linear-to-br from-primary via-accent to-secondary min-h-screen py-12 px-2 flex items-center overflow-hidden">
      <div className="container lg:flex items-center justify-between gap-4 lg:w-[90%] mx-auto">
        {/* content */}
        <div className="w-full lg:max-w-[50%] p-3 flex flex-col gap-3 md:gap-3 lg:gap-2">
          <h5 className="flex items-center gap-2 w-70 p-1 justify-center rounded-2xl bg-[#fbf6f2]">
            <span>
              <LuSparkles className="text-[#fbceae]" />
            </span>
            <span className="text-slate-800/80 text-sm font-normal font-sans">
              AI-personalized weather concierge
            </span>
          </h5>
          <h2 className="text-5xl md:text-6xl font-semibold font-sans text-slate-800/80 leading-15">
            Your sky, <br className="block md:hidden lg:block" />
            <span className="text-[#fbceae]">delivered daily.</span>
          </h2>
          <p className="text-[18px] font-sans text-slate-500 max-w-125 md:max-w-150 lg:max-w-125 font-normal leading-8">
            Tell pingcast where you are and where to reach you. Every morning
            you'll get a warm, AI-personalized forecast; what to wear, what to
            bring, what to enjoy.
          </p>
          <img
            className="w-125 md:w-full lg:w-125 h-75 md:h-87.5 lg:h-75 rounded-3xl"
            src="https://daily-sunshine-ai.lovable.app/assets/weather-hero-COmC8XCK.jpg"
            alt="cloud"
            width={50}
            height={50}
          />
          <div className=" md:w-[90%] flex items-center mt-2 justify-between gap-3">
            {about.map((a) => (
              <p
                key={a.title}
                className="flex items-center rounded-3xl bg-[#eef3fb]  w-[400px] p-2  justify-center gap-2 font-sans text-xs"
              >
                <span className="text-[#74beec]">{a.icon}</span>
                <span className="text-slate-600 font-normal text-[11px] md:text-xs">
                  {a.title}
                </span>
              </p>
            ))}
          </div>
        </div>

        {/* form */}
        <div className="lg:w-[50%] mt-4 md:mt-4 lg:mt-0">
          <div className="bg-linear-to-br from-card to-card2 rounded-3xl h-auto p-4 flex flex-col gap-4">
            <div className="w-[90%] mx-auto flex items-center gap-3">
              <p className="bg-[#ffa18f] p-2 rounded-full">
                <CiCloud className="text-white text-2xl font-bold" />
              </p>
              <h3 className="flex flex-col gap-1">
                <span className="font-sans font-semibold tracking-wide text-xl text-slate-800">
                  Start your daily forecast
                </span>{" "}
                <small className="text-xs font-normal text-slate-500 font-sans">
                  Takes 20 seconds. No account needed.
                </small>
              </h3>
            </div>

            <Form
              className="w-[90%] mx-auto flex flex-col gap-3"
              onSubmit={handleSubmit}
              schema={FormSchema}
              defaultValues={{ platform: "email" }}
            >
              {(methods) => (
                <SubscribeFormFields
                  methods={methods}
                  submitError={submitError}
                  submitSuccess={submitSuccess}
                  subscriptionId={subscriptionId}
                />
              )}
            </Form>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Home;
