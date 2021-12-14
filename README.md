# linkedcat
Plugin linkedcat for Magix CMS 3

Ajouter une ou plusieurs catégorie dans une catégorie ou page CMS.

## Installation
 * Décompresser l'archive dans le dossier "plugins" de magix cms
 * Connectez-vous dans l'administration de votre site internet
 * Cliquer sur l'onglet plugins du menu déroulant pour sélectionner linkedcat (catégorie liée).
 * Une fois dans le plugin, laisser faire l'auto installation
 * Il ne reste que la configuration du plugin pour correspondre avec vos données.
 * Copier le contenu du dossier **widget/public** dans le dossier de votre skin.

### Ajouter dans category.tpl la ligne suivante

```smarty
{linkedcat_data controller="category"}
{if $linkedcat != null}
    <div class="vignette-list">
        <div class="row" itemprop="mainEntity" itemscope itemtype="http://schema.org/ItemList">
            {include file="catalog/loop/category.tpl" data=$linkedcat classCol='vignette col-12 col-xs-4 col-md-4' nocache}
        </div>
    </div>
{/if}
````