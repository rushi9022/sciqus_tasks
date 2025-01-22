# sciqus_tasks Backend Tasks
**Develop a backend system for managing students and their associated courses to
showcase your backend development and SQL skills.**

![Screenshot 2025-01-22 143426](https://github.com/user-attachments/assets/392c6b3c-5c33-4f6e-8c8f-c72790701dcb)

![Screenshot 2025-01-22 143445](https://github.com/user-attachments/assets/0120da53-244f-4559-a006-37252bc912d8)

![Screenshot 2025-01-22 143500](https://github.com/user-attachments/assets/f09cdc41-e925-4415-b2f3-f6d97ea69aef)
![Screenshot 2025-01-22 143517](https://github.com/user-attachments/assets/8cb2c572-5f90-4eb6-8d38-4777105e9959)
![Screenshot 2025-01-22 143529](https://github.com/user-attachments/assets/081691e2-bba7-4f32-a43e-6cd199caf9d0)
![Screenshot 2025-01-22 143545](https://github.com/user-attachments/assets/383ecdbb-dd25-4967-afe6-9bb02510c4f9)


**Database Structure **

+-------------------+          +-------------------+          +--------------------+
|     courses       |          |     students      |          |       users         |
+-------------------+          +-------------------+          +--------------------+
| course_id (PK)    | 1   <--- | course_id (FK)    |          | user_id (PK)       |
| course_name       |          | student_id (PK)   |          | username (Unique)  |
| course_code (UQ)  |          | student_name      |          | password           |
| course_duration   |          | email (UQ)       |          | role (enum)        |
+-------------------+          +-------------------+          | email (nullable)   |
                                                              +--------------------+
