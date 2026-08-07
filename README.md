# Pingcast 🌤️

Pingcast is a weather assistant that sends you a personalized, AI-written daily weather report — delivered straight to your **Telegram** or **Email**, whenever you want it.

Instead of just showing raw numbers, Pingcast tells you what the weather actually means for your day: what to wear, whether to carry an umbrella, and a few activity suggestions that fit the conditions — written in a warm, conversational tone.

🔗 **Live at:** [pingcast.site](https://www.pingcast.site)

---

## How it works

1. A user subscribes with their **location**, **preferred delivery time**, and **platform** (Telegram or Email)
2. If Telegram is chosen, a button appears after signing up prompting the user to message the Pingcast Telegram bot — this links their chat ID to their subscription so reports can actually be delivered
3. Every minute, a scheduled job checks which subscriptions are due
4. For each due subscription:
   - Current weather + forecast is fetched for the user's location
   - The weather data is sent to an AI model, which generates a personalized, human-friendly summary
   - The summary is delivered via the user's chosen platform (Telegram bot message or email)
5. Delivery attempts are logged per subscription per day, with automatic retries if a send fails

---

## Tech Stack

| Layer | Tech |
|---|---|
| Frontend | React, Tailwind CSS |
| Backend | Laravel (modular architecture via `nwidart/laravel-modules`) |
| Database | PostgreSQL (hosted on Supabase) |
| AI Summaries | Groq (LLaMA 3.1) |
| Weather Data | WeatherAPI.com |
| Email Delivery | Resend (HTTP API) |
| Messaging | Telegram Bot API |
| Hosting (Backend) | Render (Docker) |
| Hosting (Frontend) | Vercel |
| Scheduling | Laravel Scheduler, triggered externally via cron-job.org |

---

## Features

- 📍 Location-based weather lookup (current + daily forecast)
- 🤖 AI-generated, conversational weather summaries — not just raw data
- 📬 Dual delivery channels: Telegram and Email
- ⏰ User-defined delivery time
- 🔁 Automatic retry logic for failed deliveries
- 📊 Per-subscription delivery logging (sent/failed, by date)
- 🔐 Admin-protected endpoints for monitoring subscriptions

---

## Repository Structure

This is a monorepo containing both the frontend and backend:

```
pingcast/
├── pingcast-frontend/   # React + Tailwind CSS
└── pingcast-backend/    # Laravel API
```

---

## Setup (Local Development)

### Backend

```bash
cd pingcast-backend

composer install
cp .env.example .env
php artisan key:generate

# Fill in your .env with the required credentials (see .env.example)

php artisan migrate

php artisan serve
```

### Frontend

```bash
cd pingcast-frontend

npm install
cp .env.example .env

# Point the frontend's .env at your backend API URL

npm run dev
```

---

## Note on Telegram Delivery

Subscribing with Telegram alone isn't enough to start receiving reports. After signing up, a button appears prompting the user to open the Pingcast Telegram bot and send `/start`. This step links the user's Telegram chat ID to their subscription — without it, the bot has no way to know who to message.

---

## License

This project is currently unlicensed / private. Update this section if you decide to open-source it.
