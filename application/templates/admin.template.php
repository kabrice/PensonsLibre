<?php
defined('__PENSONSLIBRE__') or die('Acces Interdit');
$respo = $_SESSION['administrateur'];
?>
<!DOCTYPE html>
<html>
<head> 
    <title>PensonsLibre</title> 
    <meta charset="UTF-8">  
    <link href="/projet web/webprojet/projet/library/bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/> 
    <link href="/projet web/webprojet/projet/css/posterPensee.css" rel="stylesheet" type="text/css"/> 
</head> 


    <body class="jumbotron"> 
    <header>

        <nav class="navbar navbar-inverse navbar-fixed-top navbar-default ">
            <div class="container">
                <div class="navbar-header">

                    <ul class="nav navbar-nav">
                        <li><a class="navbar-brand" href="?controller=apropos&action=apropos"><img src="../../images/libre_logo.png" height="25" width="38"></a></li>
                        <li>
                            <a href="?controller=apropos&action=apropos">A propos</a>
                        </li>

                    </ul>
                </div>

                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="#">Accueil</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <?php $user = \F3il\Authentication::getUserData();
                            $userName = $user['email'];
                            echo $userName;?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Profil</a></li>
                            <li><a href="?controller=utilisateur&action=deconnecter">Se Déconnecter</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
<<div><?php $this->insertView(); ?></div>
            <div class="card card-container">
                <form action="#">
                    <table>
                        <tr><textarea id="text-Ed">Hi everyone</textarea></tr>
                        <tr><textarea name="textarea" id="textarea"></textarea></tr>
                        <tr>


                            <div class="row" style="width: 580px;margin-left: 0px;">

                                <div class="img-zone text-center" id="img-zone">
                                    <div class="img-drop">
                                        <h2><small>Fait glisser une photo ici :)</small></h2>
                                        <h5><em>- ou si tu préfères -</em></h5>
                                        <h2><i class="glyphicon glyphicon-camera"></i></h2>
                                            <span class="btn btn-primary btn-file" id="btn-Ed">
                                                Sélectionne les photos sur l'ordinateur<input type="file" multiple="" accept="image/*">
                                            </span>
                                    </div>
                                </div>
                                <div class="progress hidden">
                                    <div style="width: 0%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="0" role="progressbar" class="progress-bar progress-bar-success progress-bar-striped active">
                                        <span class="sr-only">0% Complete</span>
                                    </div>
                                </div>

                            </div>
                            <div id="img-preview" class="row">

                            </div>
                        </tr>
                        <tr><button class="btn btn-lg btn-primary btn-block" id="btn-Ed2" type="submit"> Poster</button></tr>
                    </table>
                </form>
            </div>
            <!--card2-->

            <div class="card card-container" id="card-Ed">
                <form action="#">
                    <table class="table table-bordered">
                        <tr><td><small>Pensee d'un étudiant de 2ème année</small><small id="small-Ed2">Il y a 2 jours</small></td></tr>
                        <tr><td><h3>Pensee vous comme moi que monsieur Ruchaud est le prof le plus incompétent de 3il ?</h3></td></tr>
                        <tr>
                            <td><P>En effet, on ne comprend jamais rien quand il explique en TP. Et il va trop rapidement. Il
                                    explique toujours l'étape 5 alors que la majorité des étudiants est encore à l'étape 1 qu'ils
                                    ne comprennent même pas, pourtant ces TPs sont très bien expliqués.</br>
                                    Je propose que monsieur Ruchaud revoit sa pédagogie en s'inspirant de monsieur Belabdelhi par
                                    exemple.</P></td>
                        </tr>
                        <tr>
                            <td>
                                <img src="../../images/latouche.png" height="300" width="550">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <form action="#">
                                    <img src="../../images/like.png"><span>140</span>
                                    <img src="../../images/dislike.png"><span>10</span>
                                    <span style="margin-left: 30px">Contributions</span><span>(2)</span>
                                    <span style="margin-left: 30px">44</span><span>vu(es)</span>
                                    <img src="../../images/facebook_share.png">
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <td><small>Contribution d'un étudiant de 3ème année</small><small id="small-Ed2">Il y a 14min</small></td>
                        </tr>
                        <tr >
                            <!-- Contribution Pour (Cette contribution doit avoir impérativement au moins 2 paragraphes :
                                Un commençant par "Je suis d'accord  car..." et l'autre par "Je propose")-->
                            <td id="commentairePour"><P>Je suis d'accord  car une fois au cours d'une séance de TP, je l'avais interpellé pour qu'il m'explique
                                    un truc sur mon code, c'est comme si je le forçais</br>Je propose que Mr. Ruchaud soit un peu plus proche des élèves et plus motivé</P>
                                <div><img src="../../images/like.png"><span>140</span><span style="margin-left: 45px">Commentaires</span><span>(2)</span></div>

                            </td>
                        </tr>
                        <tr>
                            <td id="commentairePour"><div>Afficher les autres...</div></td>
                        </tr>
                        <tr>
                            <!-- Contribution Contre (Cette contribution doit avoir impérativement au moins 2 paragraphes :
                                Un commençant par "Je ne suis pas d'accord  car..." et l'autre par "Je propose")-->
                            <td id="commentaireContre"><P>Je suis pas d'accord, Si vous êtes étudiants et que vous ne voulez pas apprendre vos leçons, cessez
                                    d'accuser les profs, moi je me concentre pendant les TDs, je fais toujours mes TPs quand il faut et j'ai des bonnes notes.
                                    Je propose qu'on lâche Ruchaud et qu'on buche nos leçons</P>
                                <div><img src="../../images/like.png"><span>140</span><span style="margin-left: 45px">Commentaires</span><span>(2)</span></div>

                            </td>
                        </tr>
                        <tr>
                            <td id="commentaireContre"><div>Afficher les autres...</div></td>
                        </tr>
                        <tr>
                            <!-- Contribution Neutre (Cette contribution doit avoir impérativement au moins 2 paragraphes :
                                Un commençant par "Je ne suis ni d'accord, ni pas d'accord car..." et l'autre par "Je propose")-->
                            <td id="commentaireNeutre"><P>Je ne suis ni d'accord, ni pas d'accord car Monsieur Ruchaud ne changera jamais sa façon d'enseigner.
                                    Je propose qu'on arrête ce débat</P>
                                <div><img src="../../images/like.png"><span>140</span><span style="margin-left: 45px">Commentaires</span><span>(2)</span></div>

                            </td>
                        </tr>
                        <tr>
                            <td id="commentaireNeutre"><div>Afficher les autres...</div></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </main>
    <script src="/projet web/webprojet/projet/library/jquery/jquery-2.1.4.min.js" type="text/javascript"></script>
    <script src="/projet web/webprojet/projet/library/bootstrap-3.3.5-dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/projet web/webprojet/projet/js/ajax-upload.js" type="text/javascript"></script>
    <script src="/projet web/webprojet/projet/library/ckeditor/ckeditor.js" type="text/javascript"></script>
    <script>
        CKEDITOR.replace( 'textarea' );
    </script>
    </body>
</html>