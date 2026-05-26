
class Utilizador {
    constructor(id, image, username, role, email, cellphone, status, address, birthdate, pronouns, accountCreation, lastLogin) {
        this.id = id;
        this.image = image;
        this.username = username;
        this.role = role;
        this.email = email;
        this.cellphone = cellphone;
        this.status = status;
        this.address = address;
        this.birthdate = birthdate;
        this.pronouns = pronouns;
        this.accountCreation = accountCreation;
        this.lastLogin = lastLogin;
    }

    getAge() {
        const today = new Date();
        const birth = new Date(this.birthdate);
        let age = today.getFullYear() - birth.getFullYear();
        const month = today.getMonth() - birth.getMonth();
        if (month < 0 || (month === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }
}