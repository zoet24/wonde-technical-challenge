import ClassStudents from "./ClassStudents";

type Student = {
  id: string;
  forename: string;
  surname: string;
};

type Class = {
  id: string;
  name: string;
  main_teacher_id: string;
  students: Student[];
};

type TeacherClassesProps = {
  classes: Class[];
};

function TeacherClasses({ classes }: TeacherClassesProps) {
  return (
    <div>
      <h2>Classes:</h2>
      {classes.map((classInfo) => (
        <div key={classInfo.id}>
          <h3>{classInfo.name}</h3>
          <ClassStudents students={classInfo.students} />
        </div>
      ))}
    </div>
  );
}

export default TeacherClasses;
