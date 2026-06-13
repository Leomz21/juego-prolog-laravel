:- encoding(utf8).
:- use_module(library(random)).

% =========================================================
% PERSONAJES
% personaje(Nombre, Nivel, Vida).
% =========================================================

personaje('Elara', 5, 100).
personaje('Kael', 3, 80).
personaje('Rin', 7, 120).
personaje('Luna', 4, 90).
personaje('Darius', 6, 110).
personaje('Milo', 2, 70).

clase('Elara', guerrera).
clase('Kael', arquero).
clase('Rin', maga).
clase('Luna', exploradora).
clase('Darius', guerrero_pesado).
clase('Milo', aventurero).

% =========================================================
% INVENTARIO
% Ahora cada personaje tiene más de un arma posible.
% Las demás herramientas se conservan para misiones.
% =========================================================

inventario('Elara', [espada, daga, escudo, pocion]).
inventario('Kael', [arco, daga, hacha, flechas]).
inventario('Rin', [varita, espada, libro_hechizos, pocion, amuleto]).
inventario('Luna', [daga, arco, lanza, pocion, amuleto]).
inventario('Darius', [hacha, lanza, espada, escudo, pocion]).
inventario('Milo', [lanza, espada, arco, pocion, mapa]).

% =========================================================
% ARMAS
% arma(Nombre, DanioPorAtaque).
% =========================================================

arma(espada, 40).
arma(arco, 35).
arma(varita, 50).
arma(daga, 25).
arma(hacha, 60).
arma(lanza, 45).

% =========================================================
% ENEMIGOS
% enemigo(Nombre, Riesgo, Vida, DanioPorAtaque).
% =========================================================

enemigo(bruja, baja, 100, 30).
enemigo(zombi, media, 120, 20).
enemigo(vampiro, alta, 150, 45).
enemigo(demonio, maxima, 220, 65).
enemigo(dragon, maxima, 260, 80).

% Algunas armas hacen danio extra contra ciertos enemigos.
debilidad(bruja, varita).
debilidad(zombi, espada).
debilidad(vampiro, lanza).
debilidad(demonio, hacha).
debilidad(dragon, varita).
debilidad(dragon, hacha).

% =========================================================
% MISIONES
% mision(ID, Nombre, Dificultad, XP).
% =========================================================

mision(m1, 'Bosque de Sombras', 2, 50).
mision(m2, 'Cueva del Dragon', 5, 120).
mision(m3, 'Templo Antiguo', 7, 200).
mision(m4, 'Ruinas del Valle', 4, 90).
mision(m5, 'Fortaleza del Norte', 8, 260).

requiere(m2, escudo).
requiere(m2, pocion).
requiere(m3, libro_hechizos).
requiere(m3, pocion).
requiere(m4, mapa).
requiere(m5, escudo).
requiere(m5, amuleto).
requiere(m5, pocion).

% =========================================================
% UTILIDADES
% =========================================================

tiene_objeto(Personaje, Objeto) :-
    inventario(Personaje, Lista),
    member(Objeto, Lista).

texto_lista([], 'ninguno').

texto_lista(Lista, Texto) :-
    atomic_list_concat(Lista, ', ', Texto).

vida_final(VidaInicial, Danio, VidaFinal) :-
    Temporal is VidaInicial - Danio,
    (
        Temporal < 0
        -> VidaFinal = 0
        ; VidaFinal = Temporal
    ).

% =========================================================
% XP
% =========================================================

xp_acumulada(0, 0).

xp_acumulada(Nivel, Total) :-
    Nivel > 0,
    NivelAnterior is Nivel - 1,
    xp_acumulada(NivelAnterior, XPAnterior),
    Total is XPAnterior + (30 * Nivel).

xp_personaje(Personaje, XP) :-
    personaje(Personaje, Nivel, _),
    xp_acumulada(Nivel, XP).

xp_total_grupo([], 0).

xp_total_grupo([Personaje | Resto], Total) :-
    xp_personaje(Personaje, XP),
    xp_total_grupo(Resto, XPResto),
    Total is XP + XPResto.

% =========================================================
% ESTADO DE PERSONAJE
% =========================================================

estado_vida(Personaje, saludable) :-
    personaje(Personaje, _, Vida),
    Vida >= 100.

estado_vida(Personaje, estable) :-
    personaje(Personaje, _, Vida),
    Vida >= 70,
    Vida < 100.

estado_vida(Personaje, en_riesgo) :-
    personaje(Personaje, _, Vida),
    Vida < 70.

perfil_personaje(Personaje, Mensaje) :-
    \+ personaje(Personaje, _, _),
    atomic_list_concat(['El personaje', Personaje, 'no existe.'], ' ', Mensaje).

perfil_personaje(Personaje, Mensaje) :-
    personaje(Personaje, Nivel, Vida),
    clase(Personaje, Clase),
    inventario(Personaje, Inventario),
    estado_vida(Personaje, Estado),
    xp_personaje(Personaje, XP),
    texto_lista(Inventario, InventarioTexto),
    atomic_list_concat([
        Personaje, 'es de clase', Clase,
        'nivel', Nivel,
        'con', Vida, 'puntos de vida.',
        'Estado:', Estado, '.',
        'XP acumulada:', XP, '.',
        'Inventario:', InventarioTexto
    ], ' ', Mensaje).

% =========================================================
% REGLAS DE MISIONES
% =========================================================

cumple_requisitos(Personaje, MisionID) :-
    forall(requiere(MisionID, Objeto), tiene_objeto(Personaje, Objeto)).

objetos_faltantes(Personaje, MisionID, Faltantes) :-
    findall(Objeto, (
        requiere(MisionID, Objeto),
        \+ tiene_objeto(Personaje, Objeto)
    ), Faltantes).

puede_aceptar(Personaje, MisionID) :-
    personaje(Personaje, Nivel, _),
    mision(MisionID, _, Dificultad, _),
    Nivel >= Dificultad,
    cumple_requisitos(Personaje, MisionID).

mision_individual(Personaje, _, Mensaje) :-
    \+ personaje(Personaje, _, _),
    atomic_list_concat(['El personaje', Personaje, 'no existe.'], ' ', Mensaje).

mision_individual(_, MisionID, Mensaje) :-
    \+ mision(MisionID, _, _, _),
    atomic_list_concat(['La mision', MisionID, 'no existe.'], ' ', Mensaje).

mision_individual(Personaje, MisionID, Mensaje) :-
    puede_aceptar(Personaje, MisionID),
    mision(MisionID, Nombre, Dificultad, XP),
    xp_personaje(Personaje, XPActual),
    XPNuevo is XPActual + XP,
    atomic_list_concat([
        Personaje, 'puede entrar a', Nombre, '.',
        'Dificultad:', Dificultad, '.',
        'Recompensa:', XP, 'XP.',
        'XP total estimada despues de la mision:', XPNuevo
    ], ' ', Mensaje).

mision_individual(Personaje, MisionID, Mensaje) :-
    personaje(Personaje, Nivel, _),
    mision(MisionID, Nombre, Dificultad, _),
    Nivel < Dificultad,
    atomic_list_concat([
        Personaje, 'no puede entrar a', Nombre,
        'porque tiene nivel', Nivel,
        'y la mision requiere nivel', Dificultad
    ], ' ', Mensaje).

mision_individual(Personaje, MisionID, Mensaje) :-
    personaje(Personaje, _, _),
    mision(MisionID, Nombre, _, _),
    objetos_faltantes(Personaje, MisionID, Faltantes),
    Faltantes \= [],
    texto_lista(Faltantes, TextoFaltantes),
    atomic_list_concat([
        Personaje, 'no puede entrar a', Nombre,
        'porque le falta:', TextoFaltantes
    ], ' ', Mensaje).

alguien_tiene([Personaje | _], Objeto) :-
    tiene_objeto(Personaje, Objeto).

alguien_tiene([_ | Resto], Objeto) :-
    alguien_tiene(Resto, Objeto).

cumple_requisitos_grupo(Grupo, MisionID) :-
    forall(requiere(MisionID, Objeto), alguien_tiene(Grupo, Objeto)).

objetos_faltantes_grupo(Grupo, MisionID, Faltantes) :-
    findall(Objeto, (
        requiere(MisionID, Objeto),
        \+ alguien_tiene(Grupo, Objeto)
    ), Faltantes).

grupo_no_vacio([_|_]).

mision_grupal(Grupo, _, Mensaje) :-
    \+ grupo_no_vacio(Grupo),
    Mensaje = 'Debes seleccionar al menos un personaje para la mision grupal.'.

mision_grupal(_, MisionID, Mensaje) :-
    \+ mision(MisionID, _, _, _),
    atomic_list_concat(['La mision', MisionID, 'no existe.'], ' ', Mensaje).

mision_grupal(Grupo, MisionID, Mensaje) :-
    mision(MisionID, Nombre, _, XPRequerida),
    xp_total_grupo(Grupo, XPGrupo),
    XPGrupo < XPRequerida,
    atomic_list_concat(Grupo, ', ', GrupoTexto),
    atomic_list_concat([
        'El grupo formado por', GrupoTexto,
        'no puede entrar a', Nombre,
        'porque necesita', XPRequerida,
        'XP acumulada y solo tiene', XPGrupo
    ], ' ', Mensaje).

mision_grupal(Grupo, MisionID, Mensaje) :-
    mision(MisionID, Nombre, _, _),
    objetos_faltantes_grupo(Grupo, MisionID, Faltantes),
    Faltantes \= [],
    texto_lista(Faltantes, TextoFaltantes),
    atomic_list_concat(Grupo, ', ', GrupoTexto),
    atomic_list_concat([
        'El grupo formado por', GrupoTexto,
        'no puede entrar a', Nombre,
        'porque le falta:', TextoFaltantes
    ], ' ', Mensaje).

mision_grupal(Grupo, MisionID, Mensaje) :-
    mision(MisionID, Nombre, _, XP),
    xp_total_grupo(Grupo, XPGrupo),
    XPGrupo >= XP,
    cumple_requisitos_grupo(Grupo, MisionID),
    XPNuevo is XPGrupo + XP,
    atomic_list_concat(Grupo, ', ', GrupoTexto),
    atomic_list_concat([
        'El grupo formado por', GrupoTexto,
        'puede completar', Nombre, '.',
        'XP actual del grupo:', XPGrupo, '.',
        'Recompensa:', XP, 'XP.',
        'XP total estimada:', XPNuevo
    ], ' ', Mensaje).

% =========================================================
% COMBATE INDIVIDUAL
% =========================================================

danio_arma_contra(Arma, Enemigo, DanioFinal) :-
    arma(Arma, DanioBase),
    (
        debilidad(Enemigo, Arma)
        -> DanioFinal is DanioBase + 20
        ; DanioFinal is DanioBase
    ).

resultado_combate(VidaJugadorFinal, VidaEnemigoFinal, Resultado) :-
    VidaJugadorFinal =:= 0,
    VidaEnemigoFinal =:= 0,
    Resultado = 'Ambos cayeron en combate. El jugador y el enemigo se derrotaron mutuamente.'.

resultado_combate(VidaJugadorFinal, VidaEnemigoFinal, Resultado) :-
    VidaJugadorFinal > 0,
    VidaEnemigoFinal =:= 0,
    Resultado = 'Victoria. El jugador sobrevivio y el enemigo fue derrotado.'.

resultado_combate(VidaJugadorFinal, VidaEnemigoFinal, Resultado) :-
    VidaJugadorFinal =:= 0,
    VidaEnemigoFinal > 0,
    Resultado = 'Derrota. El enemigo sobrevivio y el jugador cayo en combate.'.

resultado_combate(VidaJugadorFinal, VidaEnemigoFinal, Resultado) :-
    VidaJugadorFinal > 0,
    VidaEnemigoFinal > 0,
    Resultado = 'El combate termino sin derrotar al enemigo. Ambos siguen con vida.'.

combate_individual(Personaje, _, _, _, Mensaje) :-
    \+ personaje(Personaje, _, _),
    atomic_list_concat(['El personaje', Personaje, 'no existe.'], ' ', Mensaje).

combate_individual(Personaje, Arma, _, _, Mensaje) :-
    personaje(Personaje, _, _),
    \+ arma(Arma, _),
    atomic_list_concat(['El arma', Arma, 'no existe.'], ' ', Mensaje).

combate_individual(Personaje, Arma, _, _, Mensaje) :-
    personaje(Personaje, _, _),
    arma(Arma, _),
    \+ tiene_objeto(Personaje, Arma),
    atomic_list_concat([
        Personaje, 'no puede usar', Arma,
        'porque no esta en su inventario.'
    ], ' ', Mensaje).

combate_individual(Personaje, Arma, Enemigo, _, Mensaje) :-
    personaje(Personaje, _, _),
    tiene_objeto(Personaje, Arma),
    \+ enemigo(Enemigo, _, _, _),
    atomic_list_concat(['El enemigo', Enemigo, 'no existe.'], ' ', Mensaje).

combate_individual(Personaje, Arma, Enemigo, AtaquesJugador, Mensaje) :-
    personaje(Personaje, _, VidaJugador),
    tiene_objeto(Personaje, Arma),
    arma(Arma, _),
    enemigo(Enemigo, Riesgo, VidaEnemigo, DanioEnemigo),
    danio_arma_contra(Arma, Enemigo, DanioJugadorPorAtaque),
    DanioJugadorTotal is DanioJugadorPorAtaque * AtaquesJugador,
    random_between(0, 3, AtaquesEnemigo),
    DanioEnemigoTotal is DanioEnemigo * AtaquesEnemigo,
    vida_final(VidaEnemigo, DanioJugadorTotal, VidaEnemigoFinal),
    vida_final(VidaJugador, DanioEnemigoTotal, VidaJugadorFinal),
    resultado_combate(VidaJugadorFinal, VidaEnemigoFinal, Resultado),
    atomic_list_concat([
        Personaje, 'uso', Arma, 'contra', Enemigo, '.',
        'Ataques del jugador:', AtaquesJugador, '.',
        'Danio por ataque del jugador:', DanioJugadorPorAtaque, '.',
        'Danio total causado al enemigo:', DanioJugadorTotal, '.',
        'Vida inicial del enemigo:', VidaEnemigo, '.',
        'Vida final del enemigo:', VidaEnemigoFinal, '.',
        'El enemigo era de riesgo', Riesgo, 'y ataco', AtaquesEnemigo, 'veces.',
        'Danio por ataque del enemigo:', DanioEnemigo, '.',
        'Danio total recibido:', DanioEnemigoTotal, '.',
        'Vida inicial del jugador:', VidaJugador, '.',
        'Vida final del jugador:', VidaJugadorFinal, '.',
        Resultado
    ], ' ', Mensaje).

% =========================================================
% COMBATE GRUPAL
% =========================================================

mejor_arma(Personaje, Arma, Danio) :-
    findall(D-A, (
        tiene_objeto(Personaje, A),
        arma(A, _),
        arma(A, D)
    ), Lista),
    keysort(Lista, Ordenada),
    last(Ordenada, Danio-Arma).

vida_total_grupo([], 0).

vida_total_grupo([Personaje | Resto], Total) :-
    personaje(Personaje, _, Vida),
    vida_total_grupo(Resto, RestoVida),
    Total is Vida + RestoVida.

danio_grupo([], _, _, 0).

danio_grupo([Personaje | Resto], Enemigo, AtaquesPorJugador, Total) :-
    mejor_arma(Personaje, Arma, _),
    danio_arma_contra(Arma, Enemigo, DanioPorAtaque),
    DanioPersonaje is DanioPorAtaque * AtaquesPorJugador,
    danio_grupo(Resto, Enemigo, AtaquesPorJugador, DanioResto),
    Total is DanioPersonaje + DanioResto.

armas_grupo([], []).

armas_grupo([Personaje | Resto], [Texto | RestoTextos]) :-
    mejor_arma(Personaje, Arma, Danio),
    atomic_list_concat([
        Personaje, 'usa', Arma,
        'con', Danio, 'de danio base'
    ], ' ', Texto),
    armas_grupo(Resto, RestoTextos).

combate_grupal(Grupo, _, _, Mensaje) :-
    \+ grupo_no_vacio(Grupo),
    Mensaje = 'Debes seleccionar al menos un personaje para la partida en grupo.'.

combate_grupal(_, Enemigo, _, Mensaje) :-
    \+ enemigo(Enemigo, _, _, _),
    atomic_list_concat(['El enemigo', Enemigo, 'no existe.'], ' ', Mensaje).

combate_grupal(Grupo, Enemigo, AtaquesPorJugador, Mensaje) :-
    grupo_no_vacio(Grupo),
    enemigo(Enemigo, Riesgo, VidaEnemigo, DanioEnemigo),
    vida_total_grupo(Grupo, VidaGrupo),
    danio_grupo(Grupo, Enemigo, AtaquesPorJugador, DanioGrupoTotal),
    armas_grupo(Grupo, ArmasUsadas),
    texto_lista(ArmasUsadas, ArmasTexto),
    random_between(0, 3, AtaquesEnemigo),
    DanioEnemigoTotal is DanioEnemigo * AtaquesEnemigo,
    vida_final(VidaEnemigo, DanioGrupoTotal, VidaEnemigoFinal),
    vida_final(VidaGrupo, DanioEnemigoTotal, VidaGrupoFinal),
    resultado_combate(VidaGrupoFinal, VidaEnemigoFinal, Resultado),
    atomic_list_concat(Grupo, ', ', GrupoTexto),
    atomic_list_concat([
        'El grupo formado por', GrupoTexto, 'enfrento a', Enemigo, '.',
        'Armas usadas:', ArmasTexto, '.',
        'Ataques por jugador:', AtaquesPorJugador, '.',
        'Danio total del grupo:', DanioGrupoTotal, '.',
        'Vida inicial del enemigo:', VidaEnemigo, '.',
        'Vida final del enemigo:', VidaEnemigoFinal, '.',
        'El enemigo era de riesgo', Riesgo, 'y ataco', AtaquesEnemigo, 'veces.',
        'Danio por ataque del enemigo:', DanioEnemigo, '.',
        'Danio total recibido por el grupo:', DanioEnemigoTotal, '.',
        'Vida inicial del grupo:', VidaGrupo, '.',
        'Vida final del grupo:', VidaGrupoFinal, '.',
        Resultado
    ], ' ', Mensaje).