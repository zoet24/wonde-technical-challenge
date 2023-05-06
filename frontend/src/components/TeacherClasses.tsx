import { useState } from "react";
import { FiPlus, FiMinus } from "react-icons/fi";
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
  const [openClassId, setOpenClassId] = useState<string | null>(null);

  const toggleClass = (classId: string) => {
    setOpenClassId((prevOpenClassId) =>
      prevOpenClassId === classId ? null : classId
    );
  };

  return (
    <div>
      <h2 className="mb-1">Classes:</h2>
      {classes.map((classInfo) => (
        <div
          className="p-2 border-t-2 border-b-2 border-white"
          key={classInfo.id}
        >
          <h3
            onClick={() => toggleClass(classInfo.id)}
            className="flex items-center cursor-pointer justify-between"
          >
            {classInfo.name} ({classInfo.students.length})
            {openClassId === classInfo.id ? <FiMinus /> : <FiPlus />}
          </h3>
          {openClassId === classInfo.id && (
            <ClassStudents students={classInfo.students} />
          )}
        </div>
      ))}
    </div>
  );
}

export default TeacherClasses;
