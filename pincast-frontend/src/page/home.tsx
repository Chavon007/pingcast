import { useState } from "react";
import { FiCloudRain } from "react-icons/fi";
import { IoSunnyOutline } from "react-icons/io5";
import { LuWind, LuSparkles } from "react-icons/lu";
import { CiCloud } from "react-icons/ci";
import Form from "../components/Form";
import subscribeUser from "../hook/formhook";
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

  const handleSubmit = async (data: FormDTO) => {
    setSubmitError(null);
    setSubmitSuccess(null);
    try {
      await subscribeUser(data);
      setSubmitSuccess("Successfully subscribed");
    } catch (err) {
      setSubmitError("Something went wrong. Please try again.");
    }
  };

  return (
    <div>
      {/* content */}
      <div>
        <h5>
          <span><LuSparkles /></span>
          <span>AI-personalized weather concierge</span>
        </h5>
        <h2>Your sky, delivered daily.</h2>
        <p>
          Tell pingcast where you are and where to reach you. Every morning
          you'll get a warm, AI-personalized forecast; what to wear, what to
          bring, what to enjoy.
        </p>
        <img
          src="https://daily-sunshine-ai.lovable.app/assets/weather-hero-COmC8XCK.jpg"
          alt="cloud"
        />
        <div>
          {about.map((a) => (
            <div key={a.title}>
              <p>
                <span>{a.icon}</span>
                <span>{a.title}</span>
              </p>
            </div>
          ))}
        </div>
      </div>

      {/* form */}
      <div>
        <div>
          <CiCloud />
          <h3>
            <span>Start your daily forecast</span>{" "}
            <small>Takes 20 seconds. No account needed.</small>
          </h3>
        </div>

        <Form
          onSubmit={handleSubmit}
          schema={FormSchema}
          defaultValues={{ platform: "whatsapp" }}
        >
          {(methods) => (
            <SubscribeFormFields
              methods={methods}
              submitError={submitError}
              submitSuccess={submitSuccess}
            />
          )}
        </Form>
      </div>
    </div>
  );
}

export default Home;