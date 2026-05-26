<?php

class User {

    private int $id;
    private string $username;
    private string $email;
    private ?int $image_id;
    private ?string $telefone;
    private string $password;
    private string $morada;
    private string $dt_nascimento;
    private string $dt_criacao;
    private ?string $pronomes;
    private bool $is_admin;
    private string $ultimo_login;
    private bool $is_verified;
    private ?string $verified_at;
    private string $created_at;
    private string $updated_at;
    private ?string $deleted_at;

    public function __construct(
        int $id = 0,
        string $username = '',
        string $email = '',
        ?int $image_id = null,
        ?string $telefone = null,
        string $password = '',
        string $morada = '',
        string $dt_nascimento = '',
        string $dt_criacao = '',
        ?string $pronomes = null,
        bool $is_admin = false,
        string $ultimo_login = '',
        bool $is_verified = false,
        ?string $verified_at = null,
        string $created_at = '',
        string $updated_at = '',
        ?string $deleted_at = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->image_id = $image_id;
        $this->telefone = $telefone;
        $this->password = $password;
        $this->morada = $morada;
        $this->dt_nascimento = $dt_nascimento;
        $this->dt_criacao = $dt_criacao;
        $this->pronomes = $pronomes;
        $this->is_admin = $is_admin;
        $this->ultimo_login = $ultimo_login;
        $this->is_verified = $is_verified;
        $this->verified_at = $verified_at;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username): void { $this->username = $username; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): void { $this->email = $email; }

    public function getImageId(): ?int { return $this->image_id; }
    public function setImageId(?int $image_id): void { $this->image_id = $image_id; }

    public function getTelefone(): ?string { return $this->telefone; }
    public function setTelefone(?string $telefone): void { $this->telefone = $telefone; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): void { $this->password = $password; }

    public function getMorada(): string { return $this->morada; }
    public function setMorada(string $morada): void { $this->morada = $morada; }

    public function getDtNascimento(): string { return $this->dt_nascimento; }
    public function setDtNascimento(string $dt_nascimento): void { $this->dt_nascimento = $dt_nascimento; }

    public function getDtCriacao(): string { return $this->dt_criacao; }
    public function setDtCriacao(string $dt_criacao): void { $this->dt_criacao = $dt_criacao; }

    public function getPronomes(): ?string { return $this->pronomes; }
    public function setPronomes(?string $pronomes): void { $this->pronomes = $pronomes; }

    public function isAdmin(): bool { return $this->is_admin; }
    public function setIsAdmin(bool $is_admin): void { $this->is_admin = $is_admin; }

    public function getUltimoLogin(): string { return $this->ultimo_login; }
    public function setUltimoLogin(string $ultimo_login): void { $this->ultimo_login = $ultimo_login; }

    public function isVerified(): bool { return $this->is_verified; }
    public function setIsVerified(bool $is_verified): void { $this->is_verified = $is_verified; }

    public function getVerifiedAt(): ?string { return $this->verified_at; }
    public function setVerifiedAt(?string $verified_at): void { $this->verified_at = $verified_at; }

    public function getCreatedAt(): string { return $this->created_at; }
    public function setCreatedAt(string $created_at): void { $this->created_at = $created_at; }

    public function getUpdatedAt(): string { return $this->updated_at; }
    public function setUpdatedAt(string $updated_at): void { $this->updated_at = $updated_at; }

    public function getDeletedAt(): ?string { return $this->deleted_at; }
    public function setDeletedAt(?string $deleted_at): void { $this->deleted_at = $deleted_at; }
}