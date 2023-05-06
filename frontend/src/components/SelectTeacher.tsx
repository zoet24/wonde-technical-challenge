import { useState } from "react";
import TeacherClasses from "./TeacherClasses";

type Teacher = {
  id: string;
  title: string;
  forename: string;
  surname: string;
  classes: any[];
};

type SelectTeacherProps = {
  teachers: Teacher[];
};

function SelectTeacher({ teachers }: SelectTeacherProps) {
  const [selectedTeacher, setSelectedTeacher] = useState<Teacher | null>(null);

  const handleChange = (event: React.ChangeEvent<HTMLSelectElement>) => {
    const teacherId = event.target.value;
    const teacher =
      teachers.find((teacher) => teacher.id === teacherId) || null;
    setSelectedTeacher(teacher);
  };

  return (
    <div>
      <div className="flex items-center justify-center mb-4">
        <h2 className="mr-2">Teacher:</h2>
        <select
          value={selectedTeacher ? selectedTeacher.id : ""}
          onChange={handleChange}
          className="bg-blue-dark focus:outline-none p-2 rounded-lg border-2 border-white"
        >
          <option value="">Select a teacher...</option>
          {teachers.map((teacher) => (
            <option key={teacher.id} value={teacher.id}>
              {teacher.title} {teacher.forename} {teacher.surname}
            </option>
          ))}
        </select>
      </div>

      {selectedTeacher && <TeacherClasses classes={selectedTeacher.classes} />}
    </div>
  );
}

export default SelectTeacher;
