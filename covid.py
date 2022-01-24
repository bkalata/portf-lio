import json

import requests

print(f"💡 \033[36mScript example: Getting Brazil Covid-19 datas\033[0m")

try:
  response1 = requests.get("https://covid-api.mmediagroup.fr/v1/cases?country=Brazil")

  country_datas = response1.json()

  cases = country_datas["All"]

  print("🤒 🇧🇷 Casos Confirmados:", cases["confirmed"])
  print("🥳 🇧🇷 Recuperados:", cases["recovered"])
  print("😢 🇧🇷 Mortes:", cases["deaths"])

except:
   print("Erro!") 

try:    
  response2 = requests.get("https://covid-api.mmediagroup.fr/v1/vaccines?country=Brazil")

  vaccines_datas = response2.json()

  vaccines = vaccines_datas["All"]

  print("📦 🇧🇷 Quantidade de Vacinas:", vaccines["administered"])
  print("💉 🇧🇷 Pessoas Vacinadas:", vaccines["people_vaccinated"])

except:
  print("Erro!!!") 