
class Comentario {
    constructor(id, id_post, id_utilizador, id_comentario_pai, dt_comentario, texto_comentario, like_count) {
        this.id = id;
        this.id_post = id_post;
        this.id_utilizador = id_utilizador;
        this.id_comentario_pai = id_comentario_pai;
        this.dt_comentario = dt_comentario;
        this.texto_comentario = texto_comentario;
        this.like_count = like_count;
    }
}