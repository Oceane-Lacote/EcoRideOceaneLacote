import Route from "./Route.js";
//Définir ici vos routes
export const allRoutes = [
    new Route("/", "Accueil", "/pages/home.html"),
    new Route("/signin", "Connexion", "/pages/signin.html"),
    new Route("/signup", "Inscription", "/pages/signup.html"),
    new Route("/profil", "Profil", "/pages/profil.html", "/script/profil.js"),
    new Route("/covoiturage", "Covoiturage", "/pages/covoiturage.html", "/script/covoiturage.js"),
    new Route("/gestion", "Employé", "/pages/gestion.html", "/script/gestion.js"),
    new Route("/admin", "Administrateur", "/pages/admin.html", "/script/admin.js"),
    new Route("/recherche", "Recherche", "/pages/recherche.html", "/script/recherche.js"),
    new Route("/resultat", "resultat", "/pages/resultat.html", "/script/resultat.js"),
];
//Le titre s'affiche comme ceci : Route.titre - websitename
export const websiteName = "EcoRide";