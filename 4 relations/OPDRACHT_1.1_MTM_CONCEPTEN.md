# Opdracht 1.1 – Vijf Many-to-Many concepten (Engels)

Elke entiteit heeft minimaal 2 velden. **Klas** = `SchoolGroup`, niet `Class`.

| # | Entiteit A | Velden A | Entiteit B | Velden B | Geïmplementeerd |
|---|------------|----------|------------|----------|-----------------|
| 1 | Student | name, email | SchoolGroup | name, code | Ja (opdracht 1.4) |
| 2 | Book | title, isbn | Author | name, country | Ja (opdracht 1.2) |
| 3 | Article | title, body | Tag | name, slug | Concept |
| 4 | Recipe | name, description | Ingredient | name, unit | Concept |
| 5 | Actor | name, birthYear | Film | title, releaseYear | Concept |

Koppeltabellen in Symfony hebben geen eigen entity (presentatie Many-to-Many slide 5).
