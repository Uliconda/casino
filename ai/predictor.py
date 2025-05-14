import sys
import json

categoria = sys.argv[1] if len(sys.argv) > 1 else "azar"

sugerencias = {
    "azar": ["Ruleta", "Tragamonedas", "Dados"],
    "estrategia": ["Poker", "Blackjack", "Ajedrez de apuestas"],
    "cartas": ["Solitario", "Texas Hold'em", "21"]
}

print(json.dumps(sugerencias.get(categoria, ["Juego clásico"])))