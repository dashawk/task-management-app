#!/usr/bin/env bash
set -e

# Move into client
cd "$(dirname "$0")/client"

echo "🧹 Clearing Nuxt cache..."
rm -rf .nuxt .nitro node_modules/.cache

# echo "📦 Installing dependencies..."
# pnpm install

echo "🚀 Starting Nuxt dev (logs will stream below)..."
exec pnpm dev
