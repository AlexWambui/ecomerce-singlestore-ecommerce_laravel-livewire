# DB DESIGN
```
users {
    id();
    uuid('uuid')->unique();
    string('first_name');
    string('last_name');
    string('email')->unique();
    string('phone_number')->nullable();
    string('secondary_phone_number')->nullable();
    unsignedTinyInteger('role')->default(3);
    boolean('status')->default(true);
    string('image')->nullable();
    timestamp('email_verified_at')->nullable();
    string('password');
    rememberToken();
    timestamps();
}

contact_messages {
    id();
    string('name');
    string('email');
    string('phone_number');
    string('message', 2000);
    boolean('is_read')->default(0);
    boolean('is_important')->default(0);
    string('notes')->nullable();
    timestamps();
}
```
