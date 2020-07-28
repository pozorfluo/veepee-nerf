# veepee-nerf

## notes

- Client
  - firstName
  - lastName
  - email
  - address
  - addressComplement
  - city
  - zipCode
  - -> country
  - phone
  - -> deliveryAddress
  - createdAt

- DeliveryAddress
  - -> client
  - firstName
  - lastName
  - address
  - addressComplement
  - city
  - zipCode
  - -> country
  - phone

- Country
  - name

- OrderInfo
  - -> client
  - -> product
  - paymentMethod
  - total
  - createdAt

- Product
  - sku
  - name
  - description
  - image
  - price
  - msrp
  - inventory

## todo

- [x] **Installer un nouveau projet symfony.**
- [x] **Copier-coller le controller, les assets et les templates.**
- [x] **Créer les entités nécessaires ( Symfony Entities )**
  - [x] Créer les entity avec la console CLI de Symfony ( make:entity )
  - [x] Générer les migrations à partir des entités dans la BDD à l'aide de la
        console CLI Symfony ( make:migration )
  - [x] Migrer les migrations avec la console CLI ( doctrine:migrations:migrate )
- [ ] **Créer les classes de formulaire ( Symfony Form )**
  - [x] Créer les classes Types dans le dossier Form, à partir des entités
  - [ ] Integrer \$form->createview() dans le controlleur, et passer le résultat
        de cette fonction à une variable utilisée dans le template
- [ ] **Gestion de la requête ( post-envoi du formulaire )**
  - [ ] Intégration du code $form->handleRequest($request) dans le controlleur
        pour préparer le remplissage dynamique de l'entité à partir de la requête
  - [ ] Utiliser le manager de doctrine (\$this->getDoctrine()->getManager(); )
        pour persist et flush vos entités
- [ ] **Communication à l'API Externe ( API Commerce ) :**
      **Créer la fonction d'enregistrement de la commande**
  - [ ] Préparation des dépendances installer et use guzzle avec composer
  - [ ] Instancier le Client de guzzle dans le controlleur ou dans une classe
        séparée
  - [ ] Utiliser la fonction \$client->request() pour envoyer une requête POST
        à l'API Centrale
  - [ ] Ajouter le paramètre header et configurer le champ 'Authorization' avec
        la valeur "Bearer XXXXXX" ( token donné sur la homepage de l'API )
  - [ ] Ajouter le json en tant que paramètre payload de la requête ( = Joindre
        les données requises pour l'enregistrement de la commande, et dont la
        description est donnée sur la documentation de l'API )
  - [ ] Recevoir les informations de réponse afin de vérifier qu'une commande a
        bien été enregistrée.
  - [ ] Enregistrer l'ID donné par l'API Centrale dans la BDD locale. On en aura
        besoin juste après pour mettre à jour le statut de commande.
  - [ ] Rediriger vers la page de paiement avec l'id de l'Order qui provient de
        la bdd de l'API
- [ ] **Gestion du paiement avec Stripe ( et PayPal s'il reste assez de temps )**
  - [ ] intégrer le moyen de paiement stripe et paypal avec payum
  - [ ] rediriger vers le formulaire en cas d'erreur de paiement
- [ ] **Communication à l'API Externe ( API Commerce ) :**
      **Créer la fonction de mise à jour du statut de commande**
  - [ ] Réutiliser le code précédent pour faire une requête similaire
  - [ ] Ajouter le paramètre pour identifier la bonne commande ( à changer dans
        l'url de la requête ) ​/order​/{id}​/status
  - [ ] Ajouter la payload pour indiquer quel est le nouveau statut de commande
        ( = PAID )
  - [ ] Envoi de l'email de confirmation du paiement au client avec Symfony,
        SMTP et MailCatcher.
  - [ ] Création d'un compte sur MailCatcher
  - [ ] Changer le .env afin de remplir les paramètres SMTP de MailCatcher
  - [ ] Envoyer le mail avec Symfony Mailer ( Swift Mailer )
  - [ ] Redirection vers la page de confirmation de la commande. Hurray!
