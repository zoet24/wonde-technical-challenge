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
      <h2>Classes</h2>
      {classes.map((classItem) => (
        <div key={classItem.id}>
          <h3>{classItem.name}</h3>
        </div>
      ))}
    </div>
  );
}

export default TeacherClasses;
