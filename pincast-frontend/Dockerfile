FROM node:20-alpine As build


WORKDIR /app

# copy package file first

COPY package*.json ./
RUN npm install

# copy the rest of the app and byild it

COPY . . 
RUN npm run build

# serve the built app with nginx

FROM nginx:alpine

# copy the build output from stage 1 into nginx
COPY  --from=build /app/build /usr/share/nginx/html

# expose port 80(nginx default)
EXPOSE 80

CMD [ "nginx", "-g", "daemon off;" ]