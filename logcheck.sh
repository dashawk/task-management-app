#!/usr/bin/env bash
set -u

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
GRAY='\033[0;37m'
NC='\033[0m'

# Icons
ICON_API="⚙️"
ICON_CLIENT="🖥️"
ICON_EXIT="🚪"
ICON_LOGS="📜"
ICON_ERROR="❌"
ICON_OK="✅"
ICON_CURSOR="${GREEN}➤${NC}"

# Menu options (label + icon)
labels=(
  " API (laravel.test)"
  " Client (frontend)"
  "Exit"
)
icons=(
  "$ICON_API"
  "$ICON_CLIENT"
  "$ICON_EXIT"
)

selected=0

# Open logs directly in current terminal
open_logs() {
  local container=$1
  echo -e "${YELLOW}${ICON_LOGS} Streaming ${container} logs... (press Ctrl+C to stop)${NC}"
  (cd api && ./vendor/bin/sail logs -f --tail="${TAIL:-200}" "${container}")
}

# Draw menu
menu() {
  echo -e "${CYAN}📋 Use ↑/↓ to move, Enter to run logs, Exit to quit${NC}"
  echo
  for i in "${!labels[@]}"; do
    if [[ $i -eq $selected ]]; then
      # Highlight selected option
      echo -e "  ${ICON_CURSOR} ${YELLOW} ${icons[$i]} ${labels[$i]}${NC}"
    else
      echo -e "     ${GRAY}${icons[$i]} ${labels[$i]}${NC}"
    fi
  done
}

while true; do
  clear
  menu

  # Read key
  read -rsn1 key
  if [[ $key == $'\x1b' ]]; then
    read -rsn2 -t 0.1 key
    case $key in
      "[A") # Up
        ((selected--))
        if ((selected < 0)); then selected=$((${#labels[@]} - 1)); fi
        ;;
      "[B") # Down
        ((selected++))
        if ((selected >= ${#labels[@]})); then selected=0; fi
        ;;
    esac
  elif [[ $key == "" ]]; then
    case $selected in
      0) open_logs "laravel.test" ;;
      1) open_logs "client" ;;
      2) echo -e "${GREEN}${ICON_OK} Goodbye!${NC}"; exit 0 ;;
    esac
    read -rp "Press Enter to return to menu..."
  fi
done
