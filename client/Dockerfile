FROM node:24-alpine

RUN corepack enable

RUN corepack prepare pnpm@latest --activate

WORKDIR /app

# Copy only dependency manifests first (better caching)
COPY package.json pnpm-lock.yaml* pnpm-workspace.yaml* ./

RUN pnpm install

COPY . .

# Expose the main port and HMR port
EXPOSE 3000

CMD ["pnpm", "dev", "--host", "0.0.0.0"]