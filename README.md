# UKF Web Kuriér

PSI / TP1 / PWT

## Web aplikácia pre kuriérov a eshopy

- Rozdeľovanie objednávok z rôznych eshopov najvhodnejším kuriérskym spoločnostiam podľa adresy a iných pravidiel.

# Dokumentácia systému UKF Web Kuriér

Dokumentácia sa skladá z nasledujúcich kategórií:

1. [`Prihlásenie a registrácia do systému`](#1)
2. [`Používateľské prostredie`](#2)
3. [`Vytvorenie pravidiel`](#3)
4. [`Pridávanie nových eshopov a kuriérov`](#4)
5. [`UKF Web Kuriér API`](#5)

---

# 1

## Prihlásenie a registrácia

Nasadenie systému zabezpečuje vybraný webmaster, ktorý taktiež vytvorí, resp. upraví, informácie nevyhnutné pre správne fungovanie databázy, ktorá sa stará aj o systém prihlasovania a regsitrácie.  

V systéme vždy existuje prvý účet, ktorý má absolútne práva v aplikácií, tzn. vie upravovať/vymazať,vytvárať všetky dostupné dáta.  
Údaje pre tento účet nastaví webmaster pred prvým spustením systému. Pomocou tohto účtu vie vytvoriť daľšie účty, ale už bez absolútnych práv.  

### Registrácia

Na registrovanie do systému potrebuje používateľ platnú emailovú adresu, unikátne meno a silné heslo. Po registrovaní získa prístup do systému, ale nemá žiadne povolenia ani práva. Tieto práva mu neskôr pridelí hlavný administrátor.

### Prihlásenie

Na prihlásenie potrebuje používateľ svoje meno a heslo. Opakovaný počet neúspešných prihlásení použitím nesprávneho hesla, bude mať za následok zamknutie daného účtu na dobu ktorú vie špecifikovať hlavný administrátor.


---

# 2
## Používateľské prostredie

### Tmavý a svetlý mód
Systém obsahuje jednoduché tlačidlo na prepínanie medzi tmavým a svetlým módom. Hodnota aktuálneho módu je uložená na zariadení používateľa.  
  
### Navigácia
Na navigáciu v systéme slúži menu v hornej časti obrazovky. Menu obsahuje všetky potrebné funkcie systému a odkazy na ne.  

### Vytváranie obsahu
Eshop, pravidlá, kuriéri, kuriérske služby, používatelia sa dajú vytvoriť pár klikmi v systéme. Pri každom vstupnom poli je zobrazený popis a aj krátky príklad, ako má vstup vyzerať.

---

# 3
## Vytvorenie pravidiel

### Subjekty a predikáty
Systém obsahuje predprogramované triedy pre subjekty a predikáty (spolu 10).  
Subjekty predstavujú jednotlivé dáta z objednávky, ktorú nám posiela Eshop. V systéme existujú tieto subjekty:  
- Mesto
- Krajina
- Veľkosť objednávky (počet predmetov v objednávke)
- Celková cena objednávky
- Hmotnosť objednávky  

Predikáty obsahujú porovnávacie hodnoty, ktoré využívame na vybratie toho správneho pravidla pre danú objednávku. V systéme existujú tieto predikáty:  

- Je rovný (rovná sa)
- Je rovný alebo väčší
- Je rovný alebo menší
- Je väčsí
- Je menší  

Príklad využitia subjektov a predikátov:  
Prichádza objednávka z Eshopu, kde sa píše, že celková hmotnosť objednávky je 25 kilogramov.
V systéme máme zadefinované pravidlo pre tento Eshop, ktoré vraví, že ak je hmotnosť objednávky (subjekt) 
rovná alebo väčšia (predikát) ako 15 kilogramov, tak túto objednávku spracuje kuriér DPD.
Objednávka má hmotnosť 25 kilogramov a teda toto pravidlo vyhovuje podmienke.  
  
### Vytváranie pravidiel v UI  
Na vytvorenie pravidiel máme modálové okno, ktoré obsahuje všetky potrebné formuláre na vytvorenie funkčného pravidla. 
Administrátor zadá názov pravidla, ďalej hodnotu pravidla, ktorá budé použítá na porovnávanie so subjektom, vyberie konkrétny Eshop, kuriérsku službu, vyberie správny subjekt a predikát pre toto pravidlo a uloží ho.

---

# 4
## Pridávanie nových eshopov a kuriérov

### Eshopy
Na vytvorenie Eshopu potrebuje administrátor vedieť len jeho názov. Po vytvorení eshopu využíva administrátor ID eshopu, resp. jeho názov pri vytváraní pravidiel, kuriérov atď.  

### Kuréri a služby
Pre vytvorenie kuriéra je potrebné vedieť jeho názov, kód (označenie) a Eshop, ku ktorému patrí.  
Kuriérske služby vytvára administrátor tak, že vloží názov služby a kód služby do formulára a ako posledné si vyberie presného kuriéra.

---

# 5
## UKF Web Kuriér API  

### Prijímanie POST dopytu z Eshopu 
Eshopy posielajú do nášho systému JSON schému na adresu */public/api/pravidla*, v nasledovnom tvare:  
```
{
    "isCity": "Berlín",
    "isCountry": "Nemecko",
    "orderSize": 4,
    "orderSum": 543,
    "orderWeight": 55,
    "eshop": "Datart"
}
```  
Subjekty "isCity", "isCountry", atď. sa používajú pre vybratie správneho pravidla a teda je nevyhnutné aby boli posielané v presnom tvare a bez chyby.
Každá schéma tiež obsahuje názov Eshopu, aby nedošlo k vybratiu nesprávnych pravidiel.  

UKF Web Kuriér túto požiadavku spracuje, a pošle naspäť nasledovnú JSON schému:  
```
{
    "service_code": "GLSNAD50KG",
    "service_name": "Objednávka nad 50 kg",
    "courier_name": "GLS"
}
```  
Táto požiadavka obsahuje kód a názov kuriérskej služby a taktiež názov kuriéra. Eshop tieto údaje ďalej spracuje podľa svojho uváženia.

---


# Záver

Projekt je napísaný vo frameworku Laravel 8, využíva frontend framework Vuejs 3 a databázu MySQL.

## ScrumTeamOne členovia

- Bc. Richard Gahér
  - Backend, Frontend, Dokumentácia, Team Leader
- Bc. Jakub Hodoši
  - Backend, Frontend, Databázy, Gitmaster
- Bc. Peter Iždinský
  - Backend, Frontend, API programátor, Mysliteľ
- Bc. Andrej Bíro
    - Databázový špecialista, motivátor, backend, frontend
- Bc. Martin Paučo
  - Backend, Frontend, tester


