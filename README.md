# Tests Personnalités

Créez et affichez facilement des tests de personnalité dynamiques sur votre site WordPress.

## 🚀 Fonctionnalités principales

- **Création de tests personnalisés** via une interface d’administration intuitive (Custom Post Type).
- **Ajout de questions, réponses et règles de résultats** grâce à une metabox dynamique.
- **Affichage sur le site** avec un shortcode simple : `[personality_test id="123"]`
- **Interface utilisateur moderne** : responsive, accessible, transitions douces, design épuré.
- **Résultats dynamiques** selon les réponses de l’utilisateur et vos critères de scoring.
- **Aucune dépendance externe** (pas de framework JS requis côté frontend).

## 🛠️ Installation

1. Téléchargez ou clonez ce dépôt dans le dossier `wp-content/plugins/` de votre installation WordPress.
2. Activez le plugin depuis le menu Extensions de WordPress.

## ✍️ Utilisation

### 1. Création d’un test
- Rendez-vous dans le menu « Tests de personnalité » de l’administration WordPress.
- Cliquez sur « Ajouter » pour créer un nouveau test.
- Remplissez les questions, réponses et règles de résultats via la metabox dédiée.
- Publiez ou mettez à jour le test.

### 2. Affichage sur le site
- Insérez le shortcode suivant dans une page ou un article :

```[personality_test id="123"]```

Remplacez `123` par l’ID du test voulu (visible dans l’URL d’édition du test).

### 3. Exemple de rendu

![Exemple d’affichage du test de personnalité](screenshot-1.png)

## 📝 Changelog
- **1.1.0** : Metabox dynamique, affichage frontend modernisé, meilleure compatibilité Gutenberg.
- **1.0.0** : Version initiale.

## 📄 Licence
GPLv2 ou ultérieure — voir [LICENSE](LICENSE) pour plus de détails.

## 👤 Auteur & Crédits
Développé par Grégoire Boisseau / [EsperluWeb](https://github.com/esperluweb)

---

Pour toute suggestion, bug ou contribution : ouvrez une issue ou une pull request sur [Github](https://github.com/esperluweb/tests-personnalites)
