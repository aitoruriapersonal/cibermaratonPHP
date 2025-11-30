#!/usr/bin/env bash

# Configuración
URL="http://www.ajedrez.deporte-universitario.com/backend/operativa/busquedaCasosAnalisis.php"
LOG_DIR="/var/log/cibermaratonSh"
DATE="$(date +%Y-%m-%d)"
OUT_FILE="$LOG_DIR/$DATE.log"
TMP_FILE="$(mktemp)"

# Asegurar directorio de logs
mkdir -p "$LOG_DIR"
chmod 750 "$LOG_DIR" 2>/dev/null || true

# Ejecutable curl por su ruta absoluta para evitar problemas en cron
CURL_BIN="/usr/bin/curl"

# Hacer la petición HTTP a un fichero temporal
"$CURL_BIN" --fail -sS --retry 3 --max-time 60 "$URL" -o "$TMP_FILE"
CURL_EXIT=$?

if [ $CURL_EXIT -eq 0 ]; then
  # Añadir separador y timestamp antes del contenido para cada ejecución (append)
  {
    printf "\n\n# --- Fetched at %s ---\n" "$(date -u +'%Y-%m-%dT%H:%M:%SZ')"
    cat "$TMP_FILE"
  } >> "$OUT_FILE"
  rm -f "$TMP_FILE"
  exit 0
else
  # En caso de fallo, guarda un log de error
  echo "[$(date -u +'%Y-%m-%dT%H:%M:%SZ')] ERROR: curl exit code $CURL_EXIT when fetching $URL" >> "$LOG_DIR/fetch_errors.log"
  rm -f "$TMP_FILE"
  exit $CURL_EXIT
fi